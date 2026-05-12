# Rent a Car — Sistema de Gestão de Reservas

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat-square&logo=postgresql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![Status](https://img.shields.io/badge/Estado-Em%20Desenvolvimento-yellow?style=flat-square)

Aplicação web desenvolvida em **Laravel 12** para gestão de reservas de veículos. O sistema tem um catálogo público acessível sem registo, um fluxo de reserva com verificação de disponibilidade em tempo real, e controlo de acessos com três níveis distintos (cliente, funcionário, administrador).

---

## Índice

1. [Visão Geral](#visão-geral)
2. [Tecnologias](#tecnologias)
3. [Organização do Código](#organização-do-código)
4. [Funcionalidades e Acesso](#funcionalidades-e-acesso)
5. [Fluxo de Reserva](#fluxo-de-reserva)
6. [Regras de Negócio](#regras-de-negócio)
7. [Validação e Segurança](#validação-e-segurança)
8. [Estrutura do Projeto](#estrutura-do-projeto)
9. [Rotas e Endpoints](#rotas-e-endpoints)
10. [Instalação e Configuração](#instalação-e-configuração)
11. [Melhorias Futuras](#melhorias-futuras)

---

## Visão Geral

Desenvolvida como trabalho prático da unidade curricular de **Programação Web** (2.º ano, ERSC), a aplicação simula o sistema de gestão de uma empresa de rent-a-car — desde a consulta pública dos veículos disponíveis até à gestão operacional por parte dos funcionários.

O foco técnico do trabalho foi o controlo de concorrência nas reservas (verificação de conflitos dentro de uma transação de base de dados), a dupla validação de dados (JS no cliente seguida de validação Laravel no servidor) e o sistema de papéis implementado com middleware customizado.

---

## Tecnologias

| Camada | Tecnologia | Versão |
|---|---|---|
| Framework | Laravel | 12.x |
| Linguagem | PHP | 8.2 |
| Base de Dados | PostgreSQL | 16 |
| Templates | Blade | — |
| Estilos | Tailwind CSS | 3.x |
| Bundler | Vite | 6.x |
| JavaScript | Vanilla JS | ES2020+ |
| Autenticação | Laravel Breeze | — |

---

## Organização do Código

O projeto segue a arquitetura MVC do Laravel, com algumas convenções adicionais:

**Controllers** — um por domínio (`ReservaController`, `VeiculoController`, `AdminController`, `CatalogoController`, `HomeController`). A lógica de negócio mais complexa (cálculo de custo, deteção de conflitos) está isolada no modelo `Reserva` como métodos estáticos reutilizáveis.

**Middleware** — dois middlewares customizados (`IsFuncionario`, `IsAdmin`) protegem os grupos de rotas. O `IsFuncionario` permite acesso a funcionários e a administradores, por isso um administrador nunca fica bloqueado em funcionalidades de staff.

**Blade Components** — o componente `x-reserva-status` encapsula a apresentação dos estados de reserva, evitando duplicação entre a listagem e a página de detalhe.

**Transações DB** — a criação e edição de reservas executam a verificação de conflitos e a escrita dentro da mesma transação (`DB::beginTransaction`), para evitar que duas reservas simultâneas para o mesmo veículo sejam ambas aceites.

---

## Funcionalidades e Acesso

### Catálogo público

O catálogo (`/catalogo`) é acessível sem autenticação e apresenta todos os veículos com marca, modelo, categoria, localização, preço/dia e estado. O botão de ação em cada card adapta-se ao estado do utilizador:

| Estado | Ação |
|---|---|
| Visitante | "Reservar →" redireciona para `/login` |
| Cliente | "Reservar →" abre o formulário com o veículo pré-selecionado |
| Funcionário / Admin | "Editar" redireciona para o painel de gestão do veículo |

A pré-seleção é feita via query string (`/reservas/create?veiculo={id}`).

### Funcionalidades por papel

| Ação | Cliente | Funcionário | Admin |
|---|:---:|:---:|:---:|
| Ver catálogo | ✓ | ✓ | ✓ |
| Criar reserva | ✓ | ✓ | ✓ |
| Ver e cancelar reservas próprias | ✓ | ✓ | ✓ |
| Ver todas as reservas | ✗ | ✓ | ✓ |
| Editar / concluir qualquer reserva | ✗ | ✓ | ✓ |
| Gerir veículos (CRUD) | ✗ | ✓ | ✓ |
| Painel de administração | ✗ | ✗ | ✓ |
| Promover / revogar funcionários | ✗ | ✗ | ✓ |
| Eliminar contas de utilizador | ✗ | ✗ | ✓ |

### Implementação dos papéis

Os três papéis são implementados da seguinte forma:

- **Cliente** — qualquer utilizador registado tem um registo na tabela `cliente`.
- **Funcionário** — tem um registo adicional na tabela `funcionario`, criado pelo administrador.
- **Administrador** — campo `is_admin = true` na tabela `users`. Tem acesso total ao sistema.

```php
// Middleware IsFuncionario — permite funcionários e admins
if (!auth()->user()->funcionario && !auth()->user()->isAdmin()) {
    abort(403);
}

// Método helper no modelo User
public function isFuncionarioOrAdmin(): bool
{
    return $this->isAdmin() || $this->funcionario !== null;
}
```

### Painel de administração

Acessível em `/admin`, exclusivo para administradores. Permite listar todos os utilizadores com o respetivo papel, promover clientes a funcionários, revogar essas permissões e eliminar contas. Admins não podem alterar outros admins nem eliminar a própria conta; utilizadores com reservas não podem ser eliminados.

---

## Fluxo de Reserva

```
Catálogo ──► [Login se necessário] ──► Formulário de Reserva
                                             │
                                    Selecionar veículo + datas
                                             │
                                    ┌────────▼────────┐
                                    │  Validação JS   │ ← datas no passado / fim < início
                                    └────────┬────────┘
                                             │ OK
                                    ┌────────▼────────┐
                                    │  POST /check    │ ← verifica conflitos no servidor
                                    └────────┬────────┘
                                             │ disponível
                                    ┌────────▼────────┐
                                    │  POST /preview  │ ← calcula custo estimado
                                    └────────┬────────┘
                                             │
                                    Preview visível + botão ativo
                                             │
                                    ┌────────▼────────┐
                                    │  POST /reservas │ ← submissão final
                                    └────────┬────────┘
                                             │
                                    Validação server-side (novamente)
                                    Transação DB + verificação de conflito
                                    Criação da reserva
                                             │
                                    Redirect ──► Lista de reservas
```

---

## Regras de Negócio

### Datas

A data de início tem de ser futura e a data de fim posterior à de início. O sistema usa `datetime-local` com precisão ao minuto.

### Deteção de conflitos

O método `Reserva::temConflito()` verifica sobreposição considerando três casos:

```
Caso 1: O início da reserva existente está dentro do novo período
        [----existente----]
             [---novo---]

Caso 2: O fim da reserva existente está dentro do novo período
                [----existente----]
        [---novo---]

Caso 3: A reserva existente engloba completamente o novo período
        [---------existente---------]
             [---novo---]
```

Apenas reservas com estado **Ativa** são consideradas. Reservas concluídas ou canceladas não bloqueiam novas marcações.

### Cálculo de custo

```
horas = ⌊minutos_totais / 60⌋
se (minutos_totais % 60 > 30) → horas++   // tolerância de 30 min
horas = max(1, horas)                      // mínimo de 1 hora

dias            = ⌊horas / 24⌋
horas_restantes = horas % 24
preço_hora      = preço_diário / 24

custo_total = (dias × preço_diário) + (horas_restantes × preço_hora)
```

O `preco_diario_usado` é guardado na reserva no momento da criação para preservar o histórico de preços caso o veículo seja atualizado posteriormente.

### Estados

```
ATIVA (1) ──► CONCLUÍDA (2)
     └──────► CANCELADA (3)
```

Reservas concluídas e canceladas são imutáveis — não podem ser editadas nem transitadas para outro estado.

---

## Validação e Segurança

| Validação | Onde | O que verifica |
|---|---|---|
| Datas válidas | JS (cliente) | Passado, ordem início/fim |
| Disponibilidade | AJAX → `/reservas/check` | Conflitos com reservas ativas |
| Preview de custo | AJAX → `/reservas/preview` | Cálculo server-side |
| Dados do formulário | Laravel `$request->validate()` | Tipos, existência em DB, regras de negócio |
| Conflitos finais | `Reserva::temConflito()` dentro de transação | Última barreira antes da escrita |

O botão de submissão fica desativado até o JS confirmar que o veículo está disponível. O servidor valida tudo de novo independentemente do estado do cliente.

Outras proteções: todos os formulários incluem `@csrf`; os modelos têm `$fillable` explícito e IDs de estado nunca vêm do request; a propriedade das reservas é verificada no servidor (`abort_if`) antes de qualquer operação; nomes de utilizadores em `onclick` usam `@json()` em vez de concatenação de strings.

---

## Estrutura do Projeto

```
rent-a-car/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php       # Gestão de utilizadores
│   │   │   ├── CatalogoController.php    # Catálogo público
│   │   │   ├── HomeController.php        # Dashboard / Landing
│   │   │   ├── ReservaController.php     # CRUD reservas + check + preview
│   │   │   └── VeiculoController.php     # CRUD veículos
│   │   └── Middleware/
│   │       ├── IsAdmin.php
│   │       └── IsFuncionario.php
│   └── Models/
│       ├── Cliente.php
│       ├── Funcionario.php
│       ├── Reserva.php                   # temConflito(), calcularCusto()
│       ├── User.php                      # isAdmin(), isFuncionarioOrAdmin()
│       └── Veiculo.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── admin/index.blade.php
│       ├── catalogo/index.blade.php
│       ├── components/reserva-status.blade.php
│       ├── layouts/
│       ├── reservas/
│       │   ├── _card.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       └── veiculos/
└── routes/web.php
```

---

## Rotas e Endpoints

### Públicas

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/` | Landing page / Dashboard |
| `GET` | `/catalogo` | Catálogo de veículos |

### Autenticadas (todos os utilizadores)

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/reservas` | Listar reservas |
| `GET` | `/reservas/create` | Formulário de criação (aceita `?veiculo={id}`) |
| `POST` | `/reservas` | Criar reserva |
| `POST` | `/reservas/check` | Verificar disponibilidade (AJAX) |
| `POST` | `/reservas/preview` | Calcular custo estimado (AJAX) |
| `GET` | `/reservas/{id}` | Detalhe da reserva |
| `PUT` | `/reservas/{id}/cancelar` | Cancelar reserva |

### Funcionários e Administradores

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/reservas/{id}/edit` | Formulário de edição |
| `PUT` | `/reservas/{id}` | Atualizar reserva |
| `PUT` | `/reservas/{id}/concluir` | Marcar como concluída |
| `GET` | `/veiculos` | Listar veículos |
| `GET` | `/veiculos/create` | Criar veículo |
| `POST` | `/veiculos` | Guardar veículo |
| `GET` | `/veiculos/{id}/edit` | Editar veículo |
| `PUT` | `/veiculos/{id}` | Atualizar veículo |
| `DELETE` | `/veiculos/{id}` | Eliminar veículo |

### Administradores

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/admin` | Painel de administração |
| `PUT` | `/admin/users/{id}/promover` | Promover a funcionário |
| `PUT` | `/admin/users/{id}/revogar` | Revogar permissões |
| `DELETE` | `/admin/users/{id}` | Eliminar conta |

## Melhorias Futuras

As funcionalidades seguintes ficaram fora do âmbito do trabalho por limitações de tempo:

- **Pesquisa e filtros no catálogo** — filtrar por categoria, localização ou intervalo de preço
- **Upload de imagens de veículos** — substituir o banner genérico por fotos reais
- **Notificações por e-mail** — confirmação de reserva e aviso de cancelamento
- **Testes automatizados** — testes de integração para o fluxo de reservas e verificação de conflitos

---

Projeto académico — Unidade Curricular de Programação Web, 2.º ano, Licenciatura em Engenharia de Redes e Sistemas de Computadores.
