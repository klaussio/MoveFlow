<p align="center">
  <strong>🚗 MoveFlow</strong><br>
  <em>Sistema de Gestão de Corridas Urbanas</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="JavaScript">
  <img src="https://img.shields.io/badge/CSS3-Modern-1572B6?style=flat-square&logo=css3&logoColor=white" alt="CSS3">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

## 📋 Sobre

O **MoveFlow** é um sistema web completo de gestão para corridas urbanas inspirado em plataformas de mobilidade como Uber, com foco administrativo e operacional. Desenvolvido com arquitetura MVC customizada em PHP, oferece uma interface moderna e profissional para gerenciamento completo de motoristas, passageiros e corridas.

## ✨ Funcionalidades

### 🔐 Autenticação
- Login seguro com bcrypt
- Proteção CSRF em todos os formulários
- Gerenciamento de sessão

### 📊 Dashboard
- Cards com métricas em tempo real (total de corridas, faturamento, motoristas ativos)
- Gráfico de faturamento dos últimos 7 dias (Canvas API)
- Gráfico de distribuição por status (Donut Chart)
- Tabela de corridas recentes
- Contadores animados

### 🚗 Motoristas (CRUD Completo)
- Cadastro com dados pessoais e veículo
- Máscaras automáticas (CPF, telefone, placa)
- Toggle de status (Disponível/Ocupado/Offline) via AJAX
- Busca e filtros por status
- Paginação

### 👥 Passageiros (CRUD Completo)
- Cadastro com validação de CPF
- Busca dinâmica com debounce
- Paginação

### 🏎️ Gestão de Corridas
- Solicitação com cálculo automático de tarifa
- Atribuição automática ou manual de motorista
- **Fluxo completo de status:**
  - Solicitada → Aceita → Em Andamento → Finalizada
  - Cancelada (com motivo)
- Timeline visual do status
- Detalhes com rota, tarifa discriminada e informações do participante
- Filtros por status, data e busca textual

### 💰 Cálculo de Tarifa
```
Tarifa = R$ 5,00 (base) + Distância × R$ 2,50/km + Duração × R$ 0,40/min
```

## 🛠️ Stack Tecnológica

| Camada | Tecnologia |
|--------|-----------|
| **Frontend** | HTML5, CSS3 (vanilla), JavaScript ES6+ |
| **Backend** | PHP 8.0+ |
| **Banco de Dados** | MySQL 8.0+ |
| **Arquitetura** | MVC Customizado |
| **Servidor** | Apache (XAMPP) |

### Design
- **Tipografia:** Inter (body) + Outfit (headings) via Google Fonts
- **Ícones:** SVG inline (Lucide-inspired)
- **Efeitos:** Glassmorphism, gradientes, micro-animações, backdrop blur
- **Gráficos:** Canvas API (sem dependências externas)

## 🚀 Instalação

### Pré-requisitos
- [XAMPP](https://www.apachefriends.org/) com PHP 8.0+ e MySQL 8.0+
- Navegador moderno

### Passo a passo

1. **Clone o repositório** na pasta `htdocs` do XAMPP:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/seu-usuario/MoveFlow.git
   ```

2. **Inicie o Apache e MySQL** no painel do XAMPP

3. **Crie o banco de dados** — acesse o phpMyAdmin ou use o terminal MySQL:
   ```bash
   mysql -u root < MoveFlow/database/schema.sql
   mysql -u root < MoveFlow/database/seed.sql
   ```

4. **Configure a URL base** (se necessário) em `app/Config/App.php`:
   ```php
   define('APP_URL', 'http://localhost/MoveFlow/public');
   ```

5. **Acesse o sistema:**
   ```
   http://localhost/MoveFlow/public
   ```

6. **Login com credenciais do seed:**
   ```
   E-mail: admin@moveflow.com
   Senha:  admin123
   ```

## 📁 Estrutura do Projeto

```
MoveFlow/
├── public/                    # Document root
│   ├── index.php              # Front controller
│   ├── .htaccess              # URL rewriting
│   └── assets/
│       ├── css/               # Design system (6 arquivos)
│       └── js/                # Módulos JavaScript (8 arquivos)
├── app/
│   ├── Config/                # Database, App, Routes
│   ├── Controllers/           # 5 controllers
│   ├── Models/                # 4 models
│   ├── Views/                 # Templates organizados por módulo
│   ├── Helpers/               # Session, Validator, Response
│   └── Core/                  # Router, Controller base, Model base
├── database/
│   ├── schema.sql             # DDL completo
│   └── seed.sql               # Dados iniciais
├── docs/
│   └── DEVELOPMENT_PLAN.md    # Documentação de desenvolvimento
└── README.md
```

## 📝 Licença

Este projeto está sob a licença MIT. Consulte o arquivo LICENSE para mais detalhes.

---

<p align="center">
  Desenvolvido com 💜 usando PHP, MySQL e muito CSS
</p>
