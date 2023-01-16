
# Copiar este arquivo para /home/sistemas/rhhealth/pipeline-devops/scripts

# Por segurança ,manter este arquivo e a pasta pai,  apenas como read and Write para os usuarios
# chown root:root /scripts
# chmod u=rwx,g=rwx,o=rx /scripts

#navega até a pasta do projeto
# Exemplo $1: /home/sistemas/rhhealth/api_rhhealth/api/
cd  $1

# Realiza o git pull com a credencial passada por parametro
# Exemplo $2:  https://gitusername:gitpassword@dev.azure.com/IT-HEALTH/ithealth_api/_git/ithealth_api
git pull $2

