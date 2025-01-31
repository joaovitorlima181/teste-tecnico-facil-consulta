## Documentação - Teste Técnico Fácil Consulta
### Instalação

- Para facilitar a instalação, executar o script **`install.sh`** que se encontra na raiz do projeto. Verificar se o usuário tem permissão de executar o script.
- Caso queira instalar manualmente:
  1. `mv .env.example .env`
  2. `composer install`
  3. `./vendor/bin/sail artisan key:generate`
  4. `./vendor/bin/sail artisan jwt:secret`
  5. `./vendor/bin/sail up -d`
  6. `./vendor/bin/sail artisan migrate`
  7. `./vendor/bin/sail artisan db:seed`
   
- Após instalação a API estará disponível na rota: **`http://localhost:4001`**
- Todas as requisições devem possuir os **Headers:** 
  - **Accept: application/json**
  - **Content-Type: application/json**
- O Token JWT para requisições que necessitam de autenticação pode ser obtido através da rota **Login**.



------------------------------------------------------------------------------------------
### Usuários
#### Criação de Usuário

<details>
 <summary><code>POST</code> <code><b>/api/register</b></code> <code>(Cria novo usuário)</code></summary>

##### Body Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | name      |  Sim | String   | Nome do usuário  |
> | email      |  Sim | String   | E-mail do usuário  |
> | password      |  Sim | String   | Senha do usuário  |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `201`         | `application/json`        | `JSON Response`                                |
> | `422`         | `application/json`                | `{"message":"Unprocessable Content", "errors" : "<Erro na validação dos dados>"}`                            |

##### Response 200 - Exemplo

> ```json
> {
> 	"message": "User created successfully",
> 	"user": {
> 		"name": "Teste",
> 		"email": "teste@teste.com",
> 		"updated_at": "2025-01-31T18:09:12.000000Z",
> 		"created_at": "2025-01-31T18:09:12.000000Z",
> 		"id": 3
> 	}
> }
> ```
</details>

#### Login

<details>
 <summary><code>POST</code> <code><b>/api/login</b></code> <code>(Login de usuário, para obter o token JWT)</code></summary>

##### Body Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | email      |  Sim | String   | E-mail do usuário  |
> | password      |  Sim | String   | Senha do usuário  |

##### Responses
> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON Response`                               |
> | `401`         | `application/json`                | `{"error": "Unauthorized"}` |

##### Response 200 - Exemplo

> ```json
> {
>	  "message": "User logged in successfully",
>	  "token": {
>		  "access_token": "<JWT_Token>",
>		"token_type": "bearer",
>		  "expires_in": 3600
>	  }
> }
> ```

</details>

#### Refresh Token

<details>
 <summary><code>POST</code> <code><b>/api/refresh</b></code> <code>(Atualiza o JWT Token - Requer Autenticação)</code></summary>

##### Headers

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | Authorization      |  Sim | String  | Token JWT

##### Responses
> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON`                               |
> | `401`         | `application/json`                | `{"error": "Unauthorized"}` |

##### Response 200 - Exemplo

> ```json
>  {
>    "message": "Token refreshed successfully",
>    "token": {
>      "access_token": "<NOVO_JWT_TOKEN>",
>      "token_type": "bearer",
>      "expires_in": 3600
>    }
>  }
> ```

</details>

------------------------------------------------------------------------------------------

### Cidades

#### Listar cidades
<details>
 <summary><code>GET</code> <code><b>/api/cidades</b></code> <code>(Lista todas as Cidades)</code></summary>

##### Query Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Não | String   | Nome da cidade  |
> | page      |  Não | String   | Página de resultatdos |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON`                                |
> | `404`         | `application/json`                | `{"error":"Cidade não encontrada"}`                            |

##### Response 200 - Exemplo

> ```json
>  {
>  	"current_page": 1,
>  	"data": [
>  		{
>  			"id": 8,
>  			"nome": "Audieberg",
>  			"estado": "CT",
>  			"created_at": "2025-01-31T12:48:51.000000Z",
>  			"updated_at": "2025-01-31T12:48:51.000000Z",
>  			"deleted_at": null
>  		}
>  	],
>  	"first_page_url": "http:\/\/127.0.0.1:4001\/api\/cidades?page=1",
>  	"from": 1,
>  	"last_page": 1,
>  	"last_page_url": "http:\/\/127.0.0.1:4001\/api\/cidades?page=1",
>  	"links": [
>  		{
>  			"url": null,
>  			"label": "&laquo; Previous",
>  			"active": false
>  		},
>  		{
>  			"url": "http:\/\/127.0.0.1:4001\/api\/cidades?page=1",
>  			"label": "1",
>  			"active": true
>  		},
>  		{
>  			"url": null,
>  			"label": "Next &raquo;",
>  			"active": false
>  		}
>  	],
>  	"next_page_url": null,
>  	"path": "http:\/\/127.0.0.1:4001\/api\/cidades",
>  	"per_page": 10,
>  	"prev_page_url": null,
>  	"to": 1,
>  	"total": 1
>  }
> ```
</details>

#### Listar médicos da cidade
<details>
 <summary><code>GET</code> <code><b>/api/cidades/{id_cidade}/medicos</b></code> <code>(Lista todos os médicos da Cidade)</code></summary>

##### Query Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Não | String   | Nome do médico  |
> | page      |  Não | String   | Página de resultatdos |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON`                                |
> | `404`         | `application/json`                | `{"error":"Médico não encontrado"}`                            |

##### Response 200 - Exemplo

> ```json
>  {
>  	"current_page": 1,
>  	"data": [
>  		{
>  			"id": 1,
>  			"nome": "Dr. Ozella Beier DVM",
>  			"especialidade": "Stevedore",
>  			"cidade_id": 2,
>  			"created_at": "2025-01-31T12:48:51.000000Z",
>  			"updated_at": "2025-01-31T12:48:51.000000Z",
>  			"deleted_at": null
>  		}
>  	],
>  	"first_page_url": "http:\/\/127.0.0.1:4001\/api\/cidades\/2\/medicos?page=1",
>  	"from": 1,
>  	"last_page": 1,
>  	"last_page_url": "http:\/\/127.0.0.1:4001\/api\/cidades\/2\/medicos?page=1",
>  	"links": [
>  		{
>  			"url": null,
>  			"label": "&laquo; Previous",
>  			"active": false
>  		},
>  		{
>  			"url": "http:\/\/127.0.0.1:4001\/api\/cidades\/2\/medicos?page=1",
>  			"label": "1",
>  			"active": true
>  		},
>  		{
>  			"url": null,
>  			"label": "Next &raquo;",
>  			"active": false
>  		}
>  	],
>  	"next_page_url": null,
>  	"path": "http:\/\/127.0.0.1:4001\/api\/cidades\/2\/medicos",
>  	"per_page": 10,
>  	"prev_page_url": null,
>  	"to": 1,
>  	"total": 1
>  }
>```
</details>

------------------------------------------------------------------------------------------

### Médicos

#### Listar médicos
<details>
 <summary><code>GET</code> <code><b>/api/medicos</b></code> <code>(Lista todos os médicos)</code></summary>

##### Query Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Não | String   | Nome do médico  |
> | page      |  Não | String   | Página de resultatdos |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON`                                |
> | `404`         | `application/json`                | `{"error":"Médico não encontrado"}`                            |

##### Response 200 - Exemplo

> ```json
>  {
>  	"current_page": 1,
>  	"data": [
>  		{
>  			"id": 22,
>  			"nome": "Nome do médico",
>  			"especialidade": "Especialidade",
>  			"cidade_id": 9,
>  			"created_at": "2025-01-31T17:06:04.000000Z",
>  			"updated_at": "2025-01-31T17:06:04.000000Z",
>  			"deleted_at": null
>  		}
>  	],
>  	"first_page_url": "http:\/\/127.0.0.1:4001\/api\/medicos?page=1",
>  	"from": 1,
>  	"last_page": 1,
>  	"last_page_url": "http:\/\/127.0.0.1:4001\/api\/medicos?page=1",
>  	"links": [
>  		{
>  			"url": null,
>  			"label": "&laquo; Previous",
>  			"active": false
>  		},
>  		{
>  			"url": "http:\/\/127.0.0.1:4001\/api\/medicos?page=1",
>  			"label": "1",
>  			"active": true
>  		},
>  		{
>  			"url": null,
>  			"label": "Next &raquo;",
>  			"active": false
>  		}
>  	],
>  	"next_page_url": null,
>  	"path": "http:\/\/127.0.0.1:4001\/api\/medicos",
>  	"per_page": 10,
>  	"prev_page_url": null,
>  	"to": 1,
>  	"total": 1
>  }
> ```
</details>

#### Criar Médico
<details>
 <summary><code>POST</code> <code><b>/api/medicos</b></code> <code>(Cria novo médico - Requer Autenticação)</code></summary>

##### Body Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Sim | String   | Nome do médico  |
> | especialidade      |  Sim | String   | Especialidade do médico  |
> | cidade_id      |  Sim | int   | Id da Cidade a qual o médico é vinculado  |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON Response`                                |
> | `422`         | `application/json`                | `{"message":"Unprocessable Content", "errors" : "<Erro na validação dos dados>"}`                            |
> | `401`         | `application/json`                | `{"message":"Unauthenticated"}`                            |

##### Response 200 - Exemplo

> ```json
> {
> 	"message": "Médico criado com sucesso",
> 	"medico": {
> 		"nome": "Nome do Médico",
> 		"especialidade": "Especialidade",
> 		"cidade_id": "9",
> 		"updated_at": "2025-01-31T17:06:04.000000Z",
> 		"created_at": "2025-01-31T17:06:04.000000Z",
> 		"id": 22
> 	}
> }
> ```
</details>

#### Listar pacientes do médico com suas consultas
<details>
 <summary><code>GET</code> <code><b>/api/medicos/{id_medico}/pacientes</b></code> <code>(Lista todos os pacientes dos médicos com suas consultas - Requer Atutenticação)</code></summary>

##### Query Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Não | String   | Nome do paciente  |
> | apenas-agendadas      |  Não | Boolean   | Listar apenas Consultas agendadas  |
> | page      |  Não | String   | Página de resultatdos |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON`                                |
> | `404`         | `application/json`                | `{"error":"Paciente não encontrado"}`                            |
> | `401`         | `application/json`                | `{"message":"Unauthenticated"}`                            |

##### Response 200 - Exemplo

> ```json
> {
> 	"pacientes": {
> 		"current_page": 1,
> 		"data": [
> 			{
> 				"id": 5,
> 				"nome": "Meggie Gulgowski",
> 				"cpf": "37886538797",
> 				"celular": "805.864.5665",
> 				"created_at": "2025-01-31T12:48:51.000000Z",
> 				"updated_at": "2025-01-31T12:48:51.000000Z",
> 				"deleted_at": null,
> 				"consultas": [
> 					{
> 						"id": 24,
> 						"medico_id": 2,
> 						"paciente_id": 5,
> 						"data": "2025-12-28",
> 						"created_at": "2025-01-31T15:24:38.000000Z",
> 						"updated_at": "2025-01-31T15:24:38.000000Z",
> 						"deleted_at": null
> 					}
> 				]
> 			}
> 		],
> 		"first_page_url": "http:\/\/127.0.0.1:4001\/api\/medicos\/2\/pacientes?page=1",
> 		"from": 1,
> 		"last_page": 1,
> 		"last_page_url": "http:\/\/127.0.0.1:4001\/api\/medicos\/2\/pacientes?page=1",
> 		"links": [
> 			{
> 				"url": null,
> 				"label": "&laquo; Previous",
> 				"active": false
> 			},
> 			{
> 				"url": "http:\/\/127.0.0.1:4001\/api\/medicos\/2\/pacientes?page=1",
> 				"label": "1",
> 				"active": true
> 			},
> 			{
> 				"url": null,
> 				"label": "Next &raquo;",
> 				"active": false
> 			}
> 		],
> 		"next_page_url": null,
> 		"path": "http:\/\/127.0.0.1:4001\/api\/medicos\/2\/pacientes",
> 		"per_page": 10,
> 		"prev_page_url": null,
> 		"to": 1,
> 		"total": 1
> 	}
> }
> ```
</details>

#### Agendar Consultas
<details>
 <summary><code>POST</code> <code><b>/api/medicos/consulta</b></code> <code>(Agenda uma nova consulta - Requer Autenticação)</code></summary>

##### Body Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | medico_id      |  Sim | String   | ID do médico  |
> | paciente_id      |  Sim | String   | ID do paciente  |
> | data    |  Sim | date   | Data da Consulta no formato Y-m-d H:i:s  |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON Response`                                |
> | `422`         | `application/json`                | `{"message":"Unprocessable Content", "errors" : "<Erro na validação dos dados>"}`                            |
> | `401`         | `application/json`                | `{"message":"Unauthenticated"}`                            |

##### Response 200 - Exemplo

> ```json
> {
> 	"message": "Consulta criada com sucesso",
> 	"consulta": {
> 		"medico_id": "2",
> 		"paciente_id": "16",
> 		"data": "2025-11-28 14:38:22",
> 		"updated_at": "2025-01-31T17:32:50.000000Z",
> 		"created_at": "2025-01-31T17:32:50.000000Z",
> 		"id": 26
> 	}
> }
> ```
</details>

------------------------------------------------------------------------------------------

### Paciente

#### Listar Pacientes
<details>
 <summary><code>GET</code> <code><b>/api/pacientes</b></code> <code>(Lista todos os pacientes - Requer autenticação)</code></summary>

##### Query Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Não | String   | Nome do paciente  |
> | page      |  Não | String   | Página de resultatdos |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON`                                |
> | `404`         | `application/json`                | `{"error":"Paciente não encontrado"}`                            |
> | `401`         | `application/json`                | `{"message":"Unauthenticated"}`                            |

##### Response 200 - Exemplo

> ```json
> {
> 	"current_page": 1,
> 	"data": [
> 		{
> 			"id": 21,
> 			"nome": "teste editado",
> 			"cpf": "00000000000",
> 			"celular": "3799999999",
> 			"created_at": "2025-01-31T12:58:56.000000Z",
> 			"updated_at": "2025-01-31T13:02:08.000000Z",
> 			"deleted_at": null
> 		},
> 		{
> 			"id": 22,
> 			"nome": "teste editado",
> 			"cpf": "CPF",
> 			"celular": "3799999999",
> 			"created_at": "2025-01-31T17:36:27.000000Z",
> 			"updated_at": "2025-01-31T17:36:27.000000Z",
> 			"deleted_at": null
> 		}
> 	],
> 	"first_page_url": "http:\/\/127.0.0.1:4001\/api\/pacientes?page=1",
> 	"from": 1,
> 	"last_page": 1,
> 	"last_page_url": "http:\/\/127.0.0.1:4001\/api\/pacientes?page=1",
> 	"links": [
> 		{
> 			"url": null,
> 			"label": "&laquo; Previous",
> 			"active": false
> 		},
> 		{
> 			"url": "http:\/\/127.0.0.1:4001\/api\/pacientes?page=1",
> 			"label": "1",
> 			"active": true
> 		},
> 		{
> 			"url": null,
> 			"label": "Next &raquo;",
> 			"active": false
> 		}
> 	],
> 	"next_page_url": null,
> 	"path": "http:\/\/127.0.0.1:4001\/api\/pacientes",
> 	"per_page": 10,
> 	"prev_page_url": null,
> 	"to": 2,
> 	"total": 2
> }
> ```
</details>

#### Cadastrar Paciente
<details>
 <summary><code>POST</code> <code><b>/api/pacientes</b></code> <code>(Cadastra novo paciente - Requer autenticação)</code></summary>

##### Body Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Sim | String   | Nome do paciente  |
> | celular      |  Sim | String   | Celular do paciente  |
> | cpf      |  Sim | String   | CPF do paciente  |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON Response`                                |
> | `422`         | `application/json`                | `{"message":"Unprocessable Content", "errors" : "<Erro na validação dos dados>"}`                            |
> | `401`         | `application/json`                | `{"message":"Unauthenticated"}`                            |


##### Response 200 - Exemplo

> ```json
> {
> 	"message": "Paciente criado com sucesso",
> 	"paciente": {
> 		"nome": "Nome do Paciente",
> 		"cpf": "CPF",
> 		"celular": "3799999999",
> 		"updated_at": "2025-01-31T17:36:27.000000Z",
> 		"created_at": "2025-01-31T17:36:27.000000Z",
> 		"id": 22
> 	}
> }
> ```
</details>

#### Cadastrar Paciente
<details>
 <summary><code>POST</code> <code><b>/api/pacientes</b></code> <code>(Cadastra novo paciente - Requer autenticação)</code></summary>

##### Body Params

> | Paramêtro      |  Obrigatório     | Tipo               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | nome      |  Sim | String   | Nome do paciente  |
> | celular      |  Sim | String   | Celular do paciente  |


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `JSON Response`                                |
> | `422`         | `application/json`                | `{"message":"Unprocessable Content", "errors" : "<Erro na validação dos dados>"}`                            |
> | `400 ` | `application/json` | ` {"error": "CPF não pode ser alterado"} ` |
> | `401`         | `application/json`                | `{"message":"Unauthenticated"}`                            |

##### Response 200 - Exemplo

> ```json
> {
> 	"message": "Paciente atualizado com sucesso",
> 	"paciente": {
> 		"id": 21,
> 		"nome": "Nome do Paciente",
> 		"cpf": "00000000000",
> 		"celular": "3799999999",
> 		"created_at": "2025-01-31T12:58:56.000000Z",
> 		"updated_at": "2025-01-31T13:02:08.000000Z",
> 		"deleted_at": null
> 	}
> }
> ```
</details>
