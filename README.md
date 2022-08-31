# Execute os containers docker todos orquestrados pelo docker-compose

##### TCC - Projeto de Pesquisa.

### Execute o build docker:
        sudo docker-compose build
    
### Start os containers:
        sudo docker-compose up -d
    
### Entre na bash do container:
        docker exec -it tcc /bin/bash
      
      - dentro da bash se deve seguir os passos:
        *instalar as dependecias com o composer php
###  Faça update do composer no container:
        composer update

###  Rotas de endpoint

###### -Rota para index [TCC]
            http://localhost:8001/

###### -Rota para acesso ao phpmyadmin [Acesso ao BD por interface gráfica]
            http://localhost:8083/
###### -Credênciais para acesso ao BD
            user: root
            pass: root

##### Iago Oliveira