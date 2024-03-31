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