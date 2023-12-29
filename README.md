<p align=center>
    <img src="public/gofit_logo_white.svg" alt="Logo Go!Fit System" width="300">
</p>

## Introdução

O GO!FIT System agora conta com um back-end dedicado e robusto, desenvolvido em Laravel 10 e banco de dados PostgreSQL. A API REST resultante é uma solução prática para a administração eficiente de instrutores de academia, visando otimizar tarefas diárias, como gerenciamento de alunos, treinos e exercícios.

## Dependências do sistema

-   [PHP 8.1 ou superior](https://www.php.net/downloads)
-   [Composer 2.6.5 ou superior](https://getcomposer.org/download/)
-   [Docker](https://docs.docker.com/desktop/install/windows-install/)

Esse projeto foi desenvolvido usando [Trunk-based Development](https://www.optimizely.com/optimization-glossary/trunk-based-development/).

## Tecnologias utilizadas

<!--Tecnologias utilizadas durante o projeto-->

### IDE utilizada para o desenvolvimento

[VSCode](https://code.visualstudio.com/)

## Setup local

Para garantir a execução correta da API no ambiente local, siga as etapas:

### Clone o projeto

```bash
git clone https://github.com/CarolineSampaio/projeto-final-modulo2
cd projeto-final-modulo2
```

### Instale as dependências do projeto

```bash
composer install
```

##

### Crie o banco de dados usando docker

```bash
docker run --name my_postgres_container -e POSTGRESQL_USERNAME=my_username -e POSTGRESQL_PASSWORD=my_password -e POSTGRESQL_DATABASE=database_name -p 5432:5432 bitnami/postgresql
```

##### A porta 5432 representa o mapeamento da porta padrão do PostgreSQL, porém, se necessário, poderá ser alterada.

#### Conecte com o dbeaver para visualizar os dados - opcional

No DBeaver, vá para "Nova Conexão", escolha "PostgreSQL", avance para a próxima aba e insira as credenciais conforme definido no comando anterior de criação do banco de dados. Teste a conexão e conclua o processo.

##

### Configure o ambiente

Na raiz do projeto, localize o arquivo .env.example, duplique-o e altere seu nome para .env, neste arquivo ficarão as credenciais do banco de dados e outras configurações sensíveis que não são compartilháveis.

Busque os parâmetros mencionados abaixo, e altere-os conforme seu ambiente local, e definições implementadas ao criar o banco de dados:

```sh
DB_CONNECTION= # Tipo de conexão
DB_HOST= # Endereço do banco de dados (normalmente 'localhost')
DB_PORT= # Porta do banco de dados (normalmente 5432)
DB_DATABASE= # Nome do banco de dados
DB_USERNAME= # Nome de usuário para acesso ao banco
DB_PASSWORD= # Senha de acesso ao banco
```

Além disso, é necessário configurar as credenciais relacionadas ao e-mail, para que quando um usuário se cadastre, seja enviado para a caixa correta no mailtrap.io:

```sh
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT= # Porta disponibilizada no mailtrap
MAIL_USERNAME= # Nome de usuário disponibilizado no mailtrap
MAIL_PASSWORD= # Senha disponibilizada no mailtrap
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Execute a seed para popular o banco de dados

```sh
php artisan db:seed PopulatePlans
```

### Execute o comando para criar as migrações do banco de dados

```sh
php artisan migrate
```

### Inicialize o servidor

```sh
php artisan serve
```

## Documentação da API

### Endpoints - Rotas Usuário

#### S01 - Cadatro de usuário

`POST /api/users`

| Parâmetro    | Tipo     | Descrição                                                                  |
| ------------ | -------- | -------------------------------------------------------------------------- |
| `name`       | `string` | **Máximo de 255 caracteres e obrigatório**.                                |
| `email`      | `string` | **Máximo de 255 caracteres, obrigatório, válido e único**.                 |
| `date_birth` | `date`   | **Máximo de 255 caracteres, obrigatório, válido e no formato yyyy-mm-dd**. |
| `cpf`        | `string` | **Máximo de 11 caracteres, obrigatório, válido e único**.                  |
| `password`   | `string` | **Máximo de 255 caracteres e obrigatório**.                                |
| `plan_id`    | `int`    | **Obrigatório**. Coluna chave estrangeira da tabela `plans`.               |

#### Exemplo de Request

Headers

```http
Accept: application/json
```

Request Body

```json
{
    "name": "Jane Doe",
    "email": "jane.doe@example.com",
    "date_birth": "1985-05-15",
    "cpf": "98765432101",
    "password": "securePass456",
    "plan_id": 2
}
```

Response

```json
{
    "message": "Usuário cadastrado com sucesso.",
    "status": 201,
    "data": {
        "name": "Jane Doe",
        "email": "jane.doe@example.com",
        "date_birth": "1985-05-15",
        "cpf": "98765432101",
        "plan_id": 2,
        "updated_at": "2023-12-29T04:12:00.000000Z",
        "created_at": "2023-12-29T04:12:00.000000Z",
        "id": 1,
        "plan": {
            "id": 2,
            "description": "PRATA",
            "limit": 20,
            "created_at": "2023-12-28T00:34:24.000000Z",
            "updated_at": "2023-12-28T00:34:24.000000Z"
        }
    }
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `201`           | Criado com sucesso       |
| `400`           | Dados inválidos          |
| `500`           | Erro interno no servidor |

##

#### S02 - Login do usuário

`POST /api/login`

| Parâmetro  | Tipo     | Descrição        |
| ---------- | -------- | ---------------- |
| `email`    | `string` | **Obrigatório**. |
| `password` | `string` | **Obrigatório**. |

#### Exemplo de Request

Headers

```http
Accept: application/json
```

Request Body

```json
{
    "email": "jane.doe@example.com",
    "password": "securePass456"
}
```

Response

```json
{
    "message": "Autenticação realizada com sucesso",
    "status": 200,
    "data": {
        "token": "1|Tx8srH8XxMGlOnzbFJTyrwxtt3osPk2FE47Mp7Gi0ca1ec9b",
        "name": "Jane Doe"
    }
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `200`           | Ok - Sucesso             |
| `400`           | Dados inválidos          |
| `401`           | Credenciais inválidas    |
| `500`           | Erro interno no servidor |

##

#### S03 - Dashboard

`GET /api/dashboard`

#### Exemplo de Request

Headers

```http
Accept: application/json
Auth: Bearer token
```

Response

```json
{
    "message": "",
    "status": 200,
    "data": {
        "registered_students": 0,
        "registered_exercises": 0,
        "current_user_plan": "Plano Prata",
        "remaining_students": 20
    }
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `200`           | Ok - Sucesso             |
| `401`           | Não autenticado          |
| `500`           | Erro interno no servidor |

##

### Endpoints - Rotas Exercícios

#### S04 - Cadastro de exercícios

`POST /api/exercises`

| Parâmetro     | Tipo     | Descrição                                   |
| ------------- | -------- | ------------------------------------------- |
| `description` | `string` | **Máximo de 255 caracteres e obrigatório**. |

#### Exemplo de Request

Headers

```http
Accept: application/json
Auth: Bearer token
```

Request Body

```json
{
    "description": "Cadeira Flexora"
}
```

Response

```json
{
    "message": "Exercício cadastrado com sucesso.",
    "status": 201,
    "data": {
        "description": "cadeira flexora",
        "id": 1
    }
}
```

| Response Status | Descrição                     |
| :-------------- | :---------------------------- |
| `201`           | Criado com sucesso            |
| `400`           | Dados inválidos               |
| `401`           | Não autenticado               |
| `409`           | Conflito, exercício já existe |
| `500`           | Erro interno no servidor      |

##

#### S05 - Listagem de exercícios

`GET /api/exercises`

#### Exemplo de Request

Headers

```http
Accept: application/json
Auth: Bearer token
```

Response

```json
{
    "message": "Exercícios cadastrados por Jane Doe, listados com sucesso",
    "status": 200,
    "data": [
        {
            "id": 3,
            "description": "agachamento"
        },
        {
            "id": 1,
            "description": "cadeira flexora"
        },
        {
            "id": 2,
            "description": "supino"
        }
    ]
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `200`           | Ok - Sucesso             |
| `401`           | Não autenticado          |
| `500`           | Erro interno no servidor |

##

#### S06 - Deleção de exercícios

`DELETE /api/exercises/:id`

| Parâmetro | Tipo  | Descrição                                                    |
| --------- | ----- | ------------------------------------------------------------ |
| `id`      | `int` | **Obrigatório e chave primária válida na tabela exercises**. |

#### Exemplo de Request:

```http
/api/exercises/1
```

Headers

```http
Accept: application/json
Auth: Bearer token
```

Response

```json
Status 204 No Content
```

| Response Status | Descrição                    |
| :-------------- | :--------------------------- |
| `204`           | Sem conteúdo (sucesso)       |
| `401`           | Não autenticado              |
| `403`           | Ação não permitida           |
| `404`           | Exercício não encontrado     |
| `409`           | Conflito por existir treinos |
| `500`           | Erro interno no servidor     |

##

### Endpoints - Rotas Estudante

#### S07 - Cadastro de estudante

`POST /api/students`

| Parâmetro      | Tipo     | Descrição                                                                  |
| -------------- | -------- | -------------------------------------------------------------------------- |
| `name`         | `string` | **Máximo de 255 caracteres e obrigatório**.                                |
| `email`        | `string` | **Máximo de 255 caracteres, obrigatório, válido e único**.                 |
| `date_birth`   | `string` | **Máximo de 255 caracteres, obrigatório, válido e no formato yyyy-mm-dd**. |
| `cpf`          | `string` | **Máximo de 11 caracteres, obrigatório, válido e único**.                  |
| `contact`      | `string` | **Máximo de 20 caracteres**.                                               |
| `cep`          | `string` | **Máximo de 20 caracteres**.                                               |
| `street`       | `string` | **Máximo de 30 caracteres**.                                               |
| `number`       | `string` | **Máximo de 30 caracteres**.                                               |
| `neighborhood` | `string` | **Máximo de 50 caracteres**.                                               |
| `city`         | `string` | **Máximo de 50 caracteres**.                                               |
| `state`        | `string` | **Máximo de 2 caracteres**.                                                |

#### Exemplo de Request

Headers

```http
Accept: application/json
Auth: Bearer token
```

Request Body

```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "date_birth": "1990-01-15",
    "cpf": "12345678909",
    "contact": "1198765-4321",
    "cep": "01234567",
    "street": "Avenida Paulista",
    "state": "SP",
    "neighborhood": "Bela Vista",
    "city": "São Paulo",
    "number": "123"
}
```

Response

```json
{
    "message": "Estudante cadastrado com sucesso.",
    "status": 201,
    "data": {
        "name": "John Doe",
        "email": "john.doe@example.com",
        "date_birth": "1990-01-15",
        "cpf": "12345678909",
        "contact": "1198765-4321",
        "cep": "01234567",
        "street": "Avenida Paulista",
        "state": "SP",
        "neighborhood": "Bela Vista",
        "city": "São Paulo",
        "number": "123",
        "updated_at": "2023-12-29T16:35:38.000000Z",
        "created_at": "2023-12-29T16:35:38.000000Z",
        "id": 1
    }
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `201`           | Criado com sucesso       |
| `400`           | Dados inválidos          |
| `401`           | Não autenticado          |
| `403`           | Limite excedido          |
| `500`           | Erro interno no servidor |

##

#### S08 - Listagem de estudantes

`GET /api/students`

Opcionalmente, pode ser adicionado ao patch um query params, de modo a realizar uma pesquisa geral com base no nome, cpf ou email.

Exemplo:

`/api/students?pesquisa_geral=john`

| Parâmetro        | Tipo     | Descrição             |
| :--------------- | :------- | :-------------------- |
| `pesquisa_geral` | `string` | `nome, cpf ou e-mail` |

#### Exemplo de Request

Headers

```http
Accept: application/json
Auth: Bearer token
```

Response

```json
{
    "message": "Estudantes listados com sucesso.",
    "status": 200,
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john.doe@example.com",
            "date_birth": "1990-01-15",
            "cpf": "12345678909",
            "contact": "1198765-4321",
            "cep": "01234567",
            "street": "Avenida Paulista",
            "number": "123",
            "neighborhood": "Bela Vista",
            "city": "São Paulo",
            "state": "SP",
            "created_at": "2023-12-29T16:35:38.000000Z",
            "updated_at": "2023-12-29T16:35:38.000000Z"
        }
    ]
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `200`           | Ok - Sucesso             |
| `401`           | Não autenticado          |
| `500`           | Erro interno no servidor |

##

#### S09 - Deleção de estudante (Soft Delete)

`DELETE /api/students/:id`

| Parâmetro | Tipo  | Descrição                                                   |
| --------- | ----- | ----------------------------------------------------------- |
| `id`      | `int` | **Obrigatório e chave primária válida na tabela students**. |

#### Exemplo de Request

```http
/api/students/1
```

Headers

```http
Accept: application/json
Auth: Bearer token
```

Response

```json
Status 204 No Content
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `204`           | Sem conteúdo (sucesso)   |
| `401`           | Não autenticado          |
| `403`           | Ação não permitida       |
| `404`           | Estudante não encontrado |
| `500`           | Erro interno no servidor |

##

#### S10 - Atualização de estudante

`PUT /api/students/:id`

| Parâmetro      | Tipo     | Descrição                                                     |
| -------------- | -------- | ------------------------------------------------------------- |
| `name`         | `string` | **Máximo de 255 caracteres**.                                 |
| `email`        | `string` | **Máximo de 255 caracteres, válido e único**.                 |
| `date_birth`   | `string` | **Máximo de 255 caracteres, válido e no formato yyyy-mm-dd**. |
| `cpf`          | `string` | **Máximo de 11 caracteres, válido e único**.                  |
| `contact`      | `string` | **Máximo de 20 caracteres**.                                  |
| `cep`          | `string` | **Máximo de 20 caracteres**.                                  |
| `street`       | `string` | **Máximo de 30 caracteres**.                                  |
| `number`       | `string` | **Máximo de 30 caracteres**.                                  |
| `neighborhood` | `string` | **Máximo de 50 caracteres**.                                  |
| `city`         | `string` | **Máximo de 50 caracteres**.                                  |
| `state`        | `string` | **Máximo de 2 caracteres**.                                   |

#### Exemplo de Request

```http
/api/students/1
```

Headers

```http
Accept: application/json
Auth: Bearer token
```

Request Body

```json
{
    "email": "john_doe@example.com",
    "date_birth": "1990-02-15"
}
```

Response

```json
{
    "message": "Aluno atualizado com sucesso.",
    "status": 200,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john_doe@example.com",
        "date_birth": "1990-02-15",
        "cpf": "12345678909",
        "contact": "1198765-4321",
        "cep": "01234567",
        "street": "Avenida Paulista",
        "number": "123",
        "neighborhood": "Bela Vista",
        "city": "São Paulo",
        "state": "SP",
        "created_at": "2023-12-29T16:35:38.000000Z",
        "updated_at": "2023-12-29T18:03:16.000000Z"
    }
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `200`           | Atualizado com sucesso   |
| `400`           | Dados inválidos          |
| `401`           | Não autenticado          |
| `403`           | Ação não permitida       |
| `404`           | Estudante não encontrado |
| `500`           | Erro interno no servidor |

##

#### S13 - Listagem de um estudante

`GET /api/students/:id`

| Parâmetro | Tipo  | Descrição                                                   |
| --------- | ----- | ----------------------------------------------------------- |
| `id`      | `int` | **Obrigatório e chave primária válida na tabela students**. |

#### Exemplo de Request

```http
/api/students/1
```

Headers

```http
Accept: application/json
Auth: Bearer token
```

Response

```json
{
    "message": "Estudante listado com sucesso.",
    "status": 200,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john_doe@example.com",
        "date_birth": "1990-02-15",
        "cpf": "12345678909",
        "contact": "1198765-4321",
        "address": {
            "cep": "01234567",
            "street": "Avenida Paulista",
            "number": "123",
            "neighborhood": "Bela Vista",
            "city": "São Paulo",
            "state": "SP"
        }
    }
}
```

| Response Status | Descrição                |
| :-------------- | :----------------------- |
| `200`           | Ok - Sucesso             |
| `401`           | Não autenticado          |
| `404`           | Estudante não encontrado |
| `500`           | Erro interno no servidor |

##

## Melhorias

<!-- acesso planejamento banco de dados:
https://dbdocs.io/caroline_08022/GOFIT_System -->

```

```
