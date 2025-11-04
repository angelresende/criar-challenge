# 💻 Teste Desenvolvedor Backend — GRUPO CRIAR

## 🧩 Introdução

Este projeto consiste na construção de uma **API RESTful** para o gerenciamento de **entidades geográficas**, **grupos (clusters)**, **campanhas** e **produtos**, conforme os requisitos do **teste técnico do GRUPO CRIAR**.

O foco principal da solução é garantir:

- ✅ **Qualidade de código**  
- 🧠 **Separação clara de responsabilidades**  
- ⚙️ **Cumprimento rigoroso das regras de negócio**, especialmente a de **“Campanha Ativa Única por Grupo”**

---

## 🛠️ Stack Utilizada

| Componente | Tecnologia |
|-------------|-------------|
| **Linguagem** | PHP 8.2+ |
| **Framework** | Laravel 12 |
| **Banco de Dados** | MySQL |
| **Contêinerização** | Docker + Docker Compose |
| **Autenticação** | Laravel Sanctum |
| **Chave primária** | ULID (Universal Lexicographically Sortable Identifier) |

---

## 🚀 Como Executar o Projeto

### 🔧 Pré-requisitos

- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados.

---

### ▶️ Passos de Execução

1. **Clonar o Repositório**

   ```bash
   git clone https://github.com/seu-usuario/criar-challenge.git
   cd criar-challenge


2.  **Configurar o Arquivo `.env`:**
    Duplique o arquivo `.env.example` para `.env` e configure as variáveis de ambiente. As configurações de banco de dados no `docker-compose.yml` são:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=mysql_db
    DB_PORT=3306
    DB_DATABASE=criar_challenge
    DB_USERNAME=root
    DB_PASSWORD=root
    ```

3.  **Construir e Iniciar os Contêineres:**
    ```bash
    docker-compose up -d --build
    ```

4.  **Executar Migrações e Seeds:**
    Acesse o contêiner da aplicação e execute os comandos:
    ```bash
    docker-compose exec app php artisan migrate --seed
    # O seed cria um usuário inicial para teste da API.
    ```

5.  **A API estará disponível em:** `http://localhost:1000`.

## 🔑 Autenticação (Laravel Sanctum)

Todas as rotas da API estão protegidas. O primeiro passo é obter um token de acesso.

**Usuário de Teste (Criado via Seed):**
* **Email:** `tester@criar.com`
* **Senha:** `password`

### Obter Token

| Método | Rota | Headers | Body (JSON) |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | Nenhum | `{"email": "tester@criar.com", "password": "password"}` |

Authorization: Bearer {token}

**Uso do Token:**
Para acessar qualquer rota protegida, inclua o token obtido no cabeçalho de todas as requisições:


| Entidade              | Rota (Exemplo)              | Descrição                    |
| :-------------------- | :-------------------------- | :--------------------------- |
| **Estados**           | `GET /api/states`           | Lista todos os estados       |
| **Cidades**           | `POST /api/cities`          | Cadastra uma nova cidade     |
| **Grupos (Clusters)** | `GET /api/groups/{id}`      | Exibe um grupo específico    |
| **Campanhas**         | `PUT /api/campaigns/{id}`   | Edita uma campanha existente |
| **Produtos**          | `DELETE /api/products/{id}` | Remove um produto            |


🧱 Arquitetura e Padrões
🧩 1. Separação de Responsabilidades (Clean Architecture)

A estrutura segue princípios de Clean Code e Service Layer Pattern.
| Camada                        | Responsabilidade                        |
|------------------------------------------------------------------------------------------------------------------ |
| **Controllers**               | Recebem as requisições, chamam os *services* e retornam as respostas HTTP.|
| **Services (`App/Services`)** | Contêm a **lógica de negócio**. 
| **Models**                    | Gerenciam relacionamentos e atuam como **DTOs**. |


### 🧠 2. Regra de Negócio Central — Campanha Ativa Única

A garantia de que cada grupo (cluster) possui apenas uma campanha ativa é crucial:

* **Implementação:** A lógica é envolvida em uma **transação de banco de dados** (`DB::transaction`).
* **Mecanismo:** O sistema **desativa campanhas anteriores** do mesmo grupo antes de ativar a nova.
* **Benefício:** Mantém a **integridade** e **atomicidade** dos dados, mesmo sob concorrência.

### 🚨 3. Tratamento de Erros

A API retorna **códigos HTTP semânticos** para informar o cliente sobre o status da requisição:

| Código | Situação |
| :--- | :--- |
| **401 Unauthorized** | Falta ou falha na autenticação (token ausente/inválido). |
| **404 Not Found** | Recurso solicitado inexistente. |
| **422 Unprocessable Entity** | Erro de validação na requisição (dados incompletos/incorretos). |
| **500 Internal Server Error** | Erro inesperado no servidor. |

---

## 🧠 Business Rules

### 🎯 Ciclo de Vida da Campanha

A tabela `campaigns` contém o campo `status`, que define o estágio atual da campanha:

| Status | Descrição | Gatilho Comum |
| :--- | :--- | :--- |
| `active` | Campanha em andamento e visível. | Ativação manual. |
| `paused` | Campanha suspensa temporariamente. | Ação do administrador. |
| `expired` | Campanha encerrada automaticamente. | Quando `end_date` é menor que a data atual. |
| `cancelled` | Campanha encerrada antes ou durante execução. | Cancelamento manual. |

### ⚙️ Regras Automáticas Sugeridas

* Apenas **uma campanha** com status `active` pode existir por cluster.
* Se `end_date` for menor que a data atual e `status = active`, o status deve ser atualizado para `expired`.
* Ao ativar uma nova campanha em um cluster, as demais campanhas ativas do mesmo grupo são desativadas automaticamente (transição para `paused` ou `expired`).

### 💰 Descontos

Cada campanha pode conter descontos configuráveis, tanto em valor fixo quanto percentual.

| Tipo | Campo | Exemplo | Observação |
| :--- | :--- | :--- | :--- |
| **Valor fixo** | `value` | `50.00` | Desconto direto em moeda (R$). |
| **Percentual** | `percentage` | `10%` | Aplicado sobre o valor total do produto. |

### 🏙️ Cidades e Clusters

* Cada cidade pertence a um **único cluster**.
* Cada cluster pode conter **várias cidades**.
* Campanhas sempre pertencem a um **único cluster**.
* Exclusão de um cluster implica em **remoção em cascata** (`cascadeOnDelete`) de campanhas e vínculos para manter a integridade dos dados.

---

📄 **Nota:** *Essas regras asseguram a consistência do domínio e a integridade dos relacionamentos entre campanhas, clusters e cidades.*

---

**✍️ Autoria**

Desenvolvido por: Angélica Resende
