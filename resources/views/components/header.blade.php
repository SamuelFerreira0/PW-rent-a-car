<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
">
    <h2 style="margin: 0;">
        @yield('title')
    </h2>

    <div>
        <a href="/" style="
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            background-color: #6b7280;
            color: white;
            margin-right: 10px;
        ">
            ← Início
        </a>

        @hasSection('back')
            @yield('back')
        @endif
    </div>
</div>