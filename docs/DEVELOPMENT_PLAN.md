# MoveFlow — Plano de Desenvolvimento

> Documentação técnica completa do sistema de gestão de corridas urbanas.

---

## 1. Visão Geral do Projeto

### 1.1 Objetivo
Desenvolver um sistema web completo de gestão para corridas urbanas inspirado em plataformas de mobilidade como Uber, com foco administrativo/operacional. O sistema permite o gerenciamento de motoristas, passageiros e corridas, com dashboard analítico e fluxo completo de status.

### 1.2 Stack Tecnológica

| Camada | Tecnologia | Justificativa |
|--------|-----------|---------------|
| Frontend | HTML5, CSS3, JS ES6+ | Máximo controle, sem dependências extras |
| Backend | PHP 8.0+ | Amplamente suportado, integração nativa com Apache |
| Banco | MySQL 8.0+ | Relacional, performático, suporte a transações |
| Servidor | Apache (XAMPP) | Ambiente de desenvolvimento completo |
| Arquitetura | MVC customizado | Separação de responsabilidades sem overhead de framework |

### 1.3 Público-alvo
Administradores de operações de transporte urbano que precisam gerenciar motoristas, passageiros e corridas de forma centralizada.

---

## 2. Arquitetura do Sistema

### 2.1 Padrão MVC

```
┌─────────────────────────────────────────────────┐
│                  BROWSER                         │
│          (HTML/CSS/JS Vanilla)                   │
└──────────────────┬──────────────────────────────┘
                   │ HTTP Request
                   ▼
┌──────────────────────────────────────────────────┐
│              public/index.php                     │
│            (Front Controller)                     │
│  ┌─────────────────────────────────────────────┐ │
│  │  Autoloader → Session → Router → Dispatch   │ │
│  └─────────────────────────────────────────────┘ │
└──────────────────┬──────────────────────────────┘
                   ▼
┌──────────────────────────────────────────────────┐
│              CONTROLLERS                          │
│  AuthController | DashboardController             │
│  DriverController | PassengerController           │
│  RideController                                   │
│  ┌────────────┐  ┌──────────┐  ┌──────────────┐ │
│  │ Validation │  │  CRUD    │  │ JSON / View  │ │
│  └────────────┘  └──────────┘  └──────────────┘ │
└──────────┬───────────────┬──────────────────────┘
           ▼               ▼
┌──────────────┐   ┌──────────────┐
│    MODELS    │   │    VIEWS     │
│  User        │   │  layouts/    │
│  Driver      │   │  auth/       │
│  Passenger   │   │  dashboard/  │
│  Ride        │   │  drivers/    │
│              │   │  passengers/ │
│  (PDO/MySQL) │   │  rides/      │
└──────┬───────┘   └──────────────┘
       ▼
┌──────────────────┐
│     MySQL DB     │
│  ┌────────────┐  │
│  │ users      │  │
│  │ drivers    │  │
│  │ passengers │  │
│  │ rides      │  │
│  └────────────┘  │
└──────────────────┘
```

### 2.2 Fluxo de Requisição
1. Requisição HTTP chega ao `public/index.php`
2. Front controller carrega autoloader e inicializa sessão
3. Router mapeia URL para Controller@método
4. Controller processa lógica, interage com Model
5. Model executa queries via PDO
6. Controller renderiza View ou retorna JSON
7. View é injetada no layout principal
8. Resposta HTML/JSON enviada ao browser

### 2.3 Sistema de Rotas

| Método | URL | Controller | Ação |
|--------|-----|-----------|------|
| GET | `/login` | AuthController | loginForm |
| POST | `/login` | AuthController | login |
| GET | `/logout` | AuthController | logout |
| GET | `/dashboard` | DashboardController | index |
| GET | `/drivers` | DriverController | index |
| GET | `/drivers/create` | DriverController | create |
| POST | `/drivers/store` | DriverController | store |
| GET | `/drivers/edit/{id}` | DriverController | edit |
| POST | `/drivers/update/{id}` | DriverController | update |
| POST | `/drivers/delete/{id}` | DriverController | delete |
| POST | `/drivers/toggle-status/{id}` | DriverController | toggleStatus |
| GET | `/passengers` | PassengerController | index |
| GET | `/passengers/create` | PassengerController | create |
| POST | `/passengers/store` | PassengerController | store |
| GET | `/passengers/edit/{id}` | PassengerController | edit |
| POST | `/passengers/update/{id}` | PassengerController | update |
| POST | `/passengers/delete/{id}` | PassengerController | delete |
| GET | `/rides` | RideController | index |
| GET | `/rides/create` | RideController | create |
| POST | `/rides/store` | RideController | store |
| GET | `/rides/show/{id}` | RideController | show |
| POST | `/rides/accept/{id}` | RideController | accept |
| POST | `/rides/start/{id}` | RideController | start |
| POST | `/rides/complete/{id}` | RideController | complete |
| POST | `/rides/cancel/{id}` | RideController | cancel |
| POST | `/rides/assign-driver/{id}` | RideController | assignDriver |

---

## 3. Modelagem do Banco de Dados

### 3.1 Diagrama ER

```
┌─────────────┐        ┌──────────────┐
│   users     │        │   drivers    │
├─────────────┤        ├──────────────┤
│ id (PK)     │        │ id (PK)      │
│ name        │        │ name         │
│ email (UK)  │        │ email (UK)   │
│ password    │        │ phone        │
│ role        │        │ cpf (UK)     │
│ created_at  │        │ cnh          │
│ updated_at  │        │ vehicle_*    │
└─────────────┘        │ status       │
                       │ rating       │
                       └──────┬───────┘
                              │ 1:N
┌──────────────┐       ┌──────┴───────┐
│  passengers  │       │    rides     │
├──────────────┤       ├──────────────┤
│ id (PK)      │──1:N──│ id (PK)      │
│ name         │       │ passenger_id │
│ email (UK)   │       │ driver_id    │
│ phone        │       │ origin_*     │
│ cpf (UK)     │       │ destination_*│
│ created_at   │       │ status       │
└──────────────┘       │ distance_km  │
                       │ duration_min │
                       │ fare         │
                       │ timestamps   │
                       └──────────────┘
```

### 3.2 Detalhamento das Tabelas

#### `users` — Administradores
| Coluna | Tipo | Restrições |
|--------|------|-----------|
| id | INT | PK, AUTO_INCREMENT |
| name | VARCHAR(100) | NOT NULL |
| email | VARCHAR(150) | NOT NULL, UNIQUE |
| password_hash | VARCHAR(255) | NOT NULL |
| role | ENUM('admin') | DEFAULT 'admin' |

#### `drivers` — Motoristas
| Coluna | Tipo | Restrições |
|--------|------|-----------|
| id | INT | PK, AUTO_INCREMENT |
| name | VARCHAR(100) | NOT NULL |
| email | VARCHAR(150) | UNIQUE |
| phone | VARCHAR(20) | NOT NULL |
| cpf | VARCHAR(14) | UNIQUE |
| cnh | VARCHAR(20) | NOT NULL |
| vehicle_plate | VARCHAR(10) | NOT NULL |
| vehicle_model | VARCHAR(80) | NOT NULL |
| vehicle_color | VARCHAR(30) | NOT NULL |
| status | ENUM | available, busy, offline |
| rating | DECIMAL(3,2) | DEFAULT 5.00 |

#### `passengers` — Passageiros
| Coluna | Tipo | Restrições |
|--------|------|-----------|
| id | INT | PK, AUTO_INCREMENT |
| name | VARCHAR(100) | NOT NULL |
| email | VARCHAR(150) | UNIQUE |
| phone | VARCHAR(20) | NOT NULL |
| cpf | VARCHAR(14) | UNIQUE |

#### `rides` — Corridas
| Coluna | Tipo | Restrições |
|--------|------|-----------|
| id | INT | PK, AUTO_INCREMENT |
| passenger_id | INT | FK → passengers, CASCADE |
| driver_id | INT | FK → drivers, SET NULL |
| origin_address | VARCHAR(255) | NOT NULL |
| destination_address | VARCHAR(255) | NOT NULL |
| status | ENUM | requested, accepted, in_progress, completed, cancelled |
| distance_km | DECIMAL(8,2) | NOT NULL |
| estimated_duration_min | INT | NOT NULL |
| fare | DECIMAL(10,2) | NOT NULL |
| requested_at → cancelled_at | DATETIME | Timestamps por estado |

---

## 4. Fluxo da Corrida

```
       ┌──────────┐
       │SOLICITADA│
       └────┬─────┘
            │ Motorista atribuído
            ▼
       ┌──────────┐
       │  ACEITA  │
       └────┬─────┘
            │ Iniciar corrida
            ▼
       ┌──────────────┐
       │ EM ANDAMENTO │
       └────┬─────────┘
            │ Finalizar corrida
            ▼
       ┌──────────────┐
       │ FINALIZADA   │
       └──────────────┘

  * Qualquer estado (exceto Finalizada/Cancelada)
    pode ser → CANCELADA (com motivo)
```

### 4.1 Regras de Negócio
- Ao aceitar: motorista muda para status `busy`
- Ao finalizar: motorista volta para `available`
- Ao cancelar: motorista (se atribuído) volta para `available`
- Tarifa calculada na criação: `BASE + (km × RATE_KM) + (min × RATE_MIN)`
- Atribuição automática seleciona o primeiro motorista disponível

---

## 5. Design System

### 5.1 Paleta de Cores
| Token | Cor | Uso |
|-------|-----|-----|
| Primary | `#7c3aed` (Violet) | Ações principais, sidebar active |
| Accent | `#06b6d4` (Cyan) | Destaques secundários |
| Success | `#22c55e` (Green) | Status positivo, conclusão |
| Warning | `#f59e0b` (Amber) | Alertas, pendências |
| Danger | `#ef4444` (Red) | Erros, cancelamentos |
| Info | `#3b82f6` (Blue) | Informações, links |
| Sidebar BG | `#0f0f1a` | Fundo escuro da sidebar |
| Body BG | `#f0f2f7` | Fundo da área de conteúdo |

### 5.2 Tipografia
- **Headings:** Outfit (400–800)
- **Body:** Inter (300–700)
- **Monospace:** JetBrains Mono (CPF, códigos)

### 5.3 Componentes
- Cards com shadow e hover lift
- Stat cards com ícone, valor animado e trend
- Botões gradient com estados (primary, success, danger, outline, ghost)
- Badges com dot indicator
- Tabelas com header, filters, pagination
- Forms com focus ring e error states
- Modais com backdrop blur
- Toasts com slide animation
- Timeline visual para status da corrida
- Visualização de rota com gradient line

---

## 6. Segurança

### 6.1 Medidas Implementadas
| Medida | Implementação |
|--------|--------------|
| Hashing de senhas | bcrypt via `password_hash()` |
| Proteção CSRF | Token por sessão em todos os forms POST |
| SQL Injection | Prepared statements (PDO) |
| XSS | `htmlspecialchars()` em todas as saídas |
| Session Fixation | `session_regenerate_id()` no login |
| Auth Guard | `requireAuth()` em todos os controllers |
| Security Headers | X-Content-Type-Options, X-Frame-Options, X-XSS-Protection |
| Cookie Security | httponly, strict mode, samesite=lax |

---

## 7. API Endpoints (AJAX)

| Endpoint | Método | Descrição | Response |
|----------|--------|-----------|----------|
| `/api/dashboard/stats` | GET | Métricas em tempo real | JSON com contadores |
| `/api/drivers/available` | GET | Motoristas disponíveis | JSON com lista |
| `/api/rides/calculate-fare` | GET | Cálculo de tarifa | JSON com valor e breakdown |
| `/drivers/toggle-status/{id}` | POST | Alternar status | JSON com novo status |

---

## 8. Dados Iniciais (Seed)

### 8.1 Admin
- **E-mail:** admin@moveflow.com
- **Senha:** admin123

### 8.2 Volumes
| Entidade | Quantidade | Observação |
|----------|-----------|------------|
| Admins | 1 | Credenciais acima |
| Motoristas | 10 | Mix de status (available, busy, offline) |
| Passageiros | 15 | Dados realistas brasileiros |
| Corridas | 30 | Distribuídas nos últimos 7 dias e todos os status |

---

## 9. Estrutura de Arquivos

```
MoveFlow/
├── public/                     # 1 arquivo PHP + 1 .htaccess
│   └── assets/
│       ├── css/                # 7 arquivos CSS
│       └── js/                 # 8 arquivos JS
├── app/
│   ├── Config/                 # 3 arquivos (Database, App, Routes)
│   ├── Controllers/            # 5 controllers
│   ├── Models/                 # 4 models
│   ├── Views/                  # 10 templates + 1 layout
│   ├── Helpers/                # 3 helpers
│   └── Core/                   # 3 classes base
├── database/                   # schema.sql + seed.sql
├── docs/                       # Esta documentação
├── README.md
└── .gitignore
```

**Total: ~50 arquivos** | **Linhas de código: ~4.000+**

---

## 10. Deploy para Produção

### 10.1 Checklist
- [ ] Alterar credenciais do banco em `Config/Database.php`
- [ ] Definir `APP_URL` correto em `Config/App.php`
- [ ] Desabilitar `display_errors` em produção
- [ ] Alterar senha do admin padrão
- [ ] Configurar HTTPS (certificado SSL)
- [ ] Habilitar `mod_rewrite` no Apache
- [ ] Verificar permissões de arquivo

### 10.2 Requisitos do Servidor
- PHP 8.0+ com extensões: pdo, pdo_mysql, mbstring
- MySQL 8.0+
- Apache com mod_rewrite habilitado
- HTTPS recomendado

---

## 11. Melhorias Futuras

- [ ] Sistema de notificações push
- [ ] Integração com API de mapas (Google Maps / OpenStreetMap)
- [ ] Cálculo de rota real (distância via API)
- [ ] Relatórios em PDF
- [ ] Multi-tenant (suporte a múltiplas operadoras)
- [ ] App mobile (PWA)
- [ ] Chat em tempo real (WebSocket)
- [ ] Sistema de pagamentos
- [ ] Avaliação pós-corrida
- [ ] Histórico de localização em tempo real

---

*Documentação gerada como parte do plano de desenvolvimento do MoveFlow v1.0.0*
