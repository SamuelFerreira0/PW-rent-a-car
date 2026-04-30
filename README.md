# 🚗 Rent a Car — Sistema de Gestão de Reservas

Aplicação web desenvolvida em **Laravel** para gestão de reservas de veículos, com separação de permissões entre clientes e funcionários e validação completa de regras de negócio.

---

## Visão Geral

Sistema para criação e gestão de reservas de veículos com:

- Validação de datas
- Verificação de conflitos
- Cálculo automático de custos
- Feedback em tempo real

O foco principal é garantir consistência entre frontend e backend.

---

## Tipos de Utilizador

### Cliente
- Criar reservas
- Consultar reservas próprias
- Ver disponibilidade em tempo real
- Ver custo estimado antes de reservar

### 🧑‍💼 Funcionário
- Criar, editar e eliminar reservas
- Concluir reservas
- Gerir veículos
- Ver todas as reservas

---

## Funcionalidades

- ✔ Verificação de conflitos de datas
- ✔ Validação de datas (frontend + backend)
- ✔ Cálculo automático de custo
- ✔ Preview em tempo real
- ✔ Bloqueio de submissão inválida
- ✔ Interface dinâmica por tipo de utilizador

---

## Regras de Negócio

### Datas
- Data início não pode ser no passado
- Data fim deve ser superior à data início

### Conflitos
- Não permite reservas sobrepostas
- Considera todos os cenários (intervalos incluídos)

### Cálculo de custo

- Baseado em preço diário
- Conversão para horas
- +1 hora se exceder 30 minutos
- Mínimo de 1 hora

Fórmula:

dias * preço_diário + horas_restantes * (preço_diário / 24)

---

## Tecnologias

- Laravel 12
- PHP 8.2
- PostgreSQL
- Blade
- JavaScript (Vanilla)

---

## Estrutura

app/
- Http/Controllers/
  - ReservaController.php
  - VeiculoController.php
- Models/
  - Reserva.php
  - Veiculo.php

resources/views/
- reservas/
- veiculos/
- home.blade.php

---

## Endpoints

- GET /reservas → listar
- POST /reservas → criar
- PUT /reservas/{id} → atualizar
- DELETE /reservas/{id} → eliminar
- PUT /reservas/{id}/concluir → concluir
- POST /reservas/check → verificar disponibilidade
- POST /reservas/preview → calcular custo

---

## Instalação

git clone https://github.com/teu-username/teu-repo.git  
cd teu-repo  

composer install  

cp .env.example .env  
php artisan key:generate  

Configurar base de dados no .env  

php artisan migrate  
php artisan serve  

---

## Autenticação

Baseado em sistema Laravel:

Campo usado:
funcionario (boolean)

---

## Validação em Tempo Real

Fluxo:

1. Utilizador escolhe datas
2. Validação local (JS)
3. Pedido ao backend (/reservas/check)
4. UI atualiza automaticamente

---

## Pontos Fortes

- Validação dupla (frontend + backend)
- Lógica de negócio consistente
- Prevenção de conflitos robusta
- Estrutura organizada

---

## Limitações

- Sem middleware de roles dedicado
- UI ainda simples
- Sem API pública documentada

---

## Melhorias Futuras

- Sistema de roles e middleware
- Dashboard com estatísticas
- Notificações
- API REST completa
- UI moderna (Tailwind / Vue)

---

## Licença

Projeto académico.