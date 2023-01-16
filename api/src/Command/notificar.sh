#
#* * * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_exames_a_vencer.sh>/tmp/api_ithealth_notifica_exames_a_vencer.log 2>&1
#0 6,8,10,12,14,16,18 * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_atestados.sh>/tmp/api_ithealth_notifica_atestados.log 2>&1
#* * * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_anexar_imagem.sh>/tmp/api_ithealth_notifica_anexar_imagem.log 2>&1
#* * * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_exames_agendados.sh>/tmp/api_ithealth_notifica_exames_agendados.log 2>&1
#* * * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_avalie_a_clinica.sh>/tmp/api_ithealth_notifica_avalie_a_clinica.log 2>&1
#* * * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_medicacao.sh>/tmp/api_ithealth_notifica_medicacao.log 2>&1
#0 7,10,13,16,19,22 * * 1-5 sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_passaporte.sh>/tmp/api_ithealth_notifica_passaporte.log 2>&1
#0 10,13,16,19,22 * * 6-7 sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_passaporte.sh>/tmp/api_ithealth_notifica_passaporte.log 2>&1
#
#* * * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_responder_exame.sh>/tmp/api_ithealth_notifica_responder_exame.log 2>&1
#0 0 * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/update_action_status.sh>/tmp/api_ithealth_update_action_status.log 2>&1
#0 0 * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_follow_up_meta_nao_atingida.sh>/tmp/api_ithealth_notifica_follow_up_meta_nao_atingida.log 2>&1
#0 0 * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_atraso_tratativa_observador.sh>/tmp/api_ithealth_notifica_atraso_tratativa_observador.log 2>&1
#
#0 7 * * * sh /home/sistemas/rhhealth/api_rhhealth/api/src/Command/notifica_pda_em_acao_melhoria.sh>/tmp/api_ithealth_update_action_status.log 2>&1



# ./bin/cake notificaExamesAgendados
# ./bin/cake notificaAtestados
# ./bin/cake notificaAvalieAClinica
# ./bin/cake notificaMedicacao
