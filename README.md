# Controle de Estacionamento

Este é um sistema para controle de estacionamento que permite as seguintes operações:

- Cadastro de categorias de veículos, contendo o valor da taxa de estacionamento
- Cadastro do veículo com vinculação da categoria
- Entrada (data e hora) - não pode dar entrada se o veículo já deu entrada sem uma saída
- Saída (data e hora) e valor de cobrança - não pode dar saída se não houver uma entrada anterior
- Demonstração de veículos com suas entradas, saídas, tempo de permanência e valor cobrado

Aumento do escopo:

- visualizacao de veiculo com dados do usuario que cadastrou e qual a sua categoria
- Cadrasto de usuarios para manipulação da aplicação
- Apenas usuários com tokens válidos podem fazer alterações/leituras
- paginacao de busca por veiculos e as suas movimentações

## Organização do Projeto

O projeto está estruturado da seguinte maneira:

- **`index.php`**: Arquivo principal que contém a interface de usuário e as chamadas para as operações do sistema.

- **`src/`**: Diretório que contém toda a aplicação.
  - **`bootstrap.php/`**: Arquivo que direciona a aplicacao e distribui dados
  - **`classes/`**: Diretório que contém as classes PHP do sistema.
  - **`Controllers/`**: Diretório que contém a lógica das regras de negócios.
  - **`Routes/`**: Diretório que contém as rotas que sao disparadas pela classe bootstrap.
  - **`Services/`**: Diretório que contém as classes responsáveis por realizar alterações no banco de dados.
    - **`AuthJwt.php`**: Configuração para lidar com as rotas autenticadas.
    - **`DbConnection.php`**: Arquivo para realizar a conexão com o banco de dados.
- **`README.md`**: Este arquivo que contém informações sobre o projeto e sua utilização.

## Pré-requisitos

Para executar este sistema, é necessário ter um servidor web (como Apache, eu usei o xampp para iniciar) e um servidor de banco de dados MySQL configurados.

### Nota de esclarecimento:

-- Devido ao fato de eu ter tido que aprender a desenvolver num ambiente novo que é o php em tao pouco tempo, não tive como implementar o uso de docker para criar as imagens do apache para o servidor web php e a imagem do banco de dados mysql

## Como Utilizar

1. Clone este repositório para o diretório do seu servidor web.
2. Configure o banco de dados conforme descrito acima.
3. Abra o navegador e acesse o diretório onde o projeto foi clonado.
4. Siga as instruções na interface para realizar as operações de cadastro de categorias, veículos, entrada e saída de veículos, e demonstração de informações.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

---
Criado por Lucas Felipe Lima Cid - lucasfelipaaa@gmail.com
