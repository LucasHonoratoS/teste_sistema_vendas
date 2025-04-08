
# 📦 Sistema de Vendas - Desafio Técnico

Este projeto foi desenvolvido como solução para um desafio técnico com prazo de 48 horas. Trata-se de um sistema completo de gerenciamento de vendas, com funcionalidades de cadastro de clientes, produtos, formas de pagamento e geração automática de parcelas. O sistema conta ainda com autenticação, geração de PDFs, filtros e um dashboard com estatísticas e gráficos.

---

## 🚀 Tecnologias Utilizadas

- **Laravel 10**
- **Bootstrap 5**
- **JavaScript / jQuery**
- **MySQL**
- **domPDF** (geração de PDFs)
- **Chart.js** (gráficos do dashboard)

---

## 🧩 Funcionalidades

### 🛒 Gestão de Vendas
- Cadastro de vendas com ou sem cliente
- Seleção de forma de pagamento
- Geração automática de parcelas com vencimento e valor

### 👥 Clientes e Produtos
- Cadastro completo de clientes e produtos
- Associação de vendas com produtos cadastrados
- Eu optei por não implementar um validador de CPF no cadastro de clientes, pois não era o foco da atividade

### 📑 Geração de PDF
- Resumo da venda em PDF
- PDF é aberto em nova aba com opção de download

### 🔐 Autenticação
- Login com Laravel Breeze
- Vendedor responsável pela venda é o usuário autenticado

### 🔎 Filtros
- Filtros por cliente e por produto na listagem de vendas

### 📊 Dashboard
- Exibição de estatísticas em cards:
  - Total de vendas
  - Total de clientes
  - Total de produtos
- Gráfico de linha com a **quantidade de vendas realizadas por dia no mês atual**

---

## 🛠️ Como Instalar e Executar

### 1. Clone o repositório

```bash
git clone https://github.com/LucasHonoratoS/teste_sistema_vendas.git
cd teste_sistema_vendas
```

### 2. Instale as dependências

```bash
composer install
npm install && npm run build
```

### 3. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o banco de dados e rode as migrations

Edite o arquivo `.env` com os dados do seu banco local.

```bash
php artisan migrate --seed
```

### 5. Inicie o servidor

```bash
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000/login)

---

## 🔐 Login de Teste

- **E-mail:** admin@email.com  
- **Senha:** admin123

---

## 🧠 Diferenciais Técnicos

- Código limpo e organizado (padrão MVC)
- Geração dinâmica de parcelas
- Dashboard com dados atualizados e visualizações via Chart.js
- Experiência fluida com geração de PDFs em nova aba
- Estrutura pronta para expansão de funcionalidades

---

## 👨‍💻 Autor

**Lucas Honorato Sacramento dos Santos**  
Desenvolvedor Fullstack em formação
[LinkedIn](https://www.linkedin.com/in/lucashsds/) | [GitHub](https://github.com/LucasHonoratoS)
