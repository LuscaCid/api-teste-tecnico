# Rotas da aplicacao

### fluxo da aplicacao:

É necessário primeiramente, cadastrar, em seguida fazer login, e por fim será retornado um jtw token de acesso que deve ser injetado nos headers.authorization para que as rotas possam ser autenticadas. Em seguida é necessário cadastrar uma categoria para que veículos cadastrados posteriormente possuam uma categoria atrelada. E por fim estes veiculos, possuindo o registro da placa como identificador unico, pode ser usado para emitir entradas e saidas, usar o registro da placa para capturar o historico de entradas e saídas daquele veículo em específico.

## A Rota base em ambiente de desenvolvimento:

-localhost/api_parking ...

### Users

POST: /users/signup 
  -- É necessário passar um email e uma senha nos campos de html (email, password) para cadastrar.

POST: /users/signin
  -- É necessário passar um email e uma senha nos campos de html (email, password) para autenticar.
  -- Exemplo de retorno:
  ````json
  {
	"access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imx1Y2FzQGUuY29tIn0.pyR7V2NyyznNFrXeg0aZGjJ_uxCnaknk3weUrtnG76U",
	"message": "Login feito com sucesso."
  }
  ````

### categories

POST: /categories/create
  -- É necessário passar um type e parking_fee nos campos de html (type, parking_fee).
  -- (type é a categoria do veiculo e parking_fee é igual a taxa por hora daquela categoria).

GET: /categories/view
  -- É retornado um vetor com as categorias criadas.

  -- exemplo de retorno: 
  ````json
  [
    {
      "id": "1",
      "type": "carro",
      "parking_fee": "10"
    },
    {
      "id": "2",
      "type": "moto",
      "parking_fee": "5"
    }
  ]
  ````

### vehicles

GET: /vehicles/vehicles_reports/:page
  -- Retorna um relatorios paginado de movimentacoes completas de veiculos na aplicação
  -- exemplo de retorno: 
  ````json
  {
	"Historico_veiculos": [
		{
			"record_id": "1",
			"license_plate": "bao2321",
			"model": "fusca",
			"inputted_at": "2024-03-31 21:13:38",
			"outputted_at": "2024-03-31 22:30:28",
			"final_price": "10",
			"permanence_time": "1 horas.",
			"type": "caminhao"
		}
	]
}
  ````

GET: /vehicles/vehicle_report/:license_plate
  -- Retorna um relatorios paginado de movimentacoes completas de um veiculo em específico retornado pela placa
  -- exemplo de retorno: 
  ````json
  {
	"Historico_relatorio": [
		{
			"record_id": "4",
			"license_plate": "jss22322",
			"model": "mercedes",
			"inputted_at": "2024-03-31 00:47:19",
			"outputted_at": "2024-03-31 00:47:54",
			"final_price": "0",
			"permanence_time": "0 horas.",
			"type": "carro"
		},
		{
			"record_id": "5",
			"license_plate": "jss22322",
			"model": "mercedes",
			"inputted_at": "2024-03-31 01:01:08",
			"outputted_at": "2024-03-31 01:01:18",
			"final_price": "0",
			"permanence_time": "0 horas.",
			"type": "carro"
		},
		{
			"record_id": "6",
			"license_plate": "jss22322",
			"model": "mercedes",
			"inputted_at": "2024-03-31 06:09:27",
			"outputted_at": "2024-03-31 09:09:33",
			"final_price": "30",
			"permanence_time": "3 horas.",
			"type": "carro"
		}
  ]
  }
  ````
  GET: /vehicles/create
  -- É necessário passar nos campos de formulário (multipart form data) os seguintes campos:
  - license_plate (valor com placa do veiculo)
  - model (o modelo do veiculo)
  - category_id (id que referencia a categoria do veiculo a ser cadastrado)

  -- exemplo de resposta:
  ````json
  {
	"response": "Veículo criado."
  }
  ````

  GET: /vehicles/license/:license_plate
  -- Rota que retorna todos os dados do veiculo passando apenas a placa para ser buscado.
  -- exemplo de resposta:
  ````json
  {
	"response": {
		"created_by": "lucas@e.com",
		"type": "carro",
		"parking_fee": "10",
		"model": "mercedes",
		"license_plate": "jss22322",
		"created_at": "2024-03-31 00:46:40"
	  }
  }
  ````
  GET: /vehicles/view/:page
  -- rota que usa da paginacao pois retorna dados fracionados sem necessariamente fazer uma busca por algo especifico.
  -- limit estipulado dentro da aplicação de 5
  -- exemplo de retorno: 
  
  /vehicles/view/1
  ````json
  "response": [
		{
			"license_plate": "eq22323",
			"created_by": "lucascid@e.com",
			"type": "caminhao",
			"parking_fee": "40",
			"model": "ferrari",
			"created_at": "2024-03-31 22:52:03"
		},
		{
			"license_plate": "bao2321",
			"created_by": "lucasd@e.com",
			"type": "caminhao",
			"parking_fee": "40",
			"model": "fusca",
			"created_at": "2024-03-31 22:13:31"
		},
  ]
  ````
### inputs (entradas)
  POST: /inputs/emit/:license_plate
  -- É necessário passar a placa do veiculo nos parametros da requisição.

### outputs (saídas)
  POST: /outputs/emit/:license_plate
  -- É necessário passar a placa do veiculo nos parametros da requisição.
  -- exemplo de retorno:
  ````json
  {
	"resposta": "Saída emitida com sucesso",
	"a_pagar": 80,
	"horas_permanencia": 2,
	"taxa_estacionamento": "40"
  }
  ````

