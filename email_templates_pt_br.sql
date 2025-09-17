-- Templates de email traduzidos para Português Brasileiro
-- Para importar manualmente no banco de dados

-- Limpar registros existentes (opcional)
-- DELETE FROM `email_templates` WHERE id IN (2,12,13,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);

-- Inserir templates traduzidos
INSERT INTO `email_templates` (`id`, `email_type`, `email_subject`, `email_body`) VALUES
(2, 'email_verification', 'Verifique seu E-mail', '<p style=\"line-height: 1.6;\">Olá <b>{customer_name}</b>,</p><p style=\"line-height: 1.6;\"><br>Por favor, clique no link abaixo para verificar seu e-mail.</p><p>{verification_link}</p><p><br></p><p>Atenciosamente,</p><p>{website_title}</p>'),

(12, 'custom_domain_connected', 'Domínio Personalizado Conectado ao Nosso Servidor', 'Olá {username},<br><br>\n\nObrigado pela sua solicitação de domínio personalizado.<br>\nSeu domínio solicitado {requested_domain} foi conectado ao seu servidor.<br>\nPor favor, <strong>limpe o cache do seu navegador</strong> e visite {requested_domain} para ver seu site portfólio.<br>\n\nSeu domínio atual: {requested_domain}.<br>\nSeu domínio anterior: {previous_domain}.<br><br>\n\nAtenciosamente,<br>\n{website_title}.<br>'),

(13, 'custom_domain_rejected', 'Solicitação de Domínio Personalizado foi Rejeitada', 'Olá {username},<br><br>\r\n\r\nObrigado pela sua solicitação de domínio personalizado.<br>\r\nInfelizmente, rejeitamos sua solicitação de domínio personalizado<br>\r\n\r\nSeu domínio solicitado: {requested_domain}.<br>\r\nSeu domínio atual: {current_domain}.<br><br>\r\n\r\nAtenciosamente,<br>\r\n{website_title}.<br>'),

(16, 'registration_with_premium_package', 'Você se registrou com sucesso', '<p>Olá {username},<br /><br />\r\n\r\nEste é um e-mail de confirmação nosso</p><p><b><span style=\"font-size:18px;\">Informações da Assinatura:</span></b><br />\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n<strong>Data de Ativação:</strong> {activation_date}<br />\r\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\r\nObrigado pela sua compra.</p><p><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br /></p>'),

(17, 'registration_with_trial_package', 'Você se registrou com sucesso', 'Olá {username},<br /><br />\r\n\r\nEste é um e-mail de confirmação nosso.<br />\r\nVocê se registrou com uma versão de teste de <span style=\"color:rgb(87,89,98);\"><b>{package_title}</b></span><br /><br />\r\n\r\n<h4>Informações da Assinatura:</h4>\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n<strong>Data de Ativação:</strong> {activation_date}<br />\r\n<strong>Data de Expiração:</strong> {expire_date}<br /><br />\r\n\r\nAnexamos uma fatura neste e-mail<br />\r\nObrigado pela sua compra.<br /><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br />'),

(18, 'registration_with_free_package', 'Você se registrou com sucesso', 'Olá {username},<br /><br />\r\n\r\nEste é um e-mail de confirmação nosso.<br />\r\nVocê adquiriu um pacote gratuito<br /><br />\r\n\r\n<h4>Informações da Assinatura:</h4>\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n<strong>Data de Ativação:</strong> {activation_date}<br />\r\n<strong>Data de Expiração:</strong> {expire_date}<br /><br />\r\n\r\nAnexamos uma fatura neste e-mail<br />\r\nObrigado pela sua compra.<br /><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br />'),

(19, 'membership_expiry_reminder', 'Sua assinatura expirará em breve', 'Olá {username},<br /><br />\r\n\r\nSua assinatura expirará em breve.<br />\r\nSua assinatura é válida até <strong>{last_day_of_membership}</strong><br />\r\nPor favor, clique aqui - {login_link} para entrar no painel e comprar um novo pacote / estender o pacote atual para renovar sua assinatura.<br /><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.'),

(20, 'membership_expired', 'Sua assinatura expirou', 'Olá {username},<br><br>\r\n\r\nSua assinatura expirou.<br>\r\nPor favor, clique aqui - {login_link} para entrar no painel e comprar um novo pacote / estender o pacote atual para continuar a assinatura.<br><br>\r\n\r\nAtenciosamente,<br>\r\n{website_title}.'),

(21, 'membership_extend', 'Sua assinatura foi estendida', '<p>Olá {username},<br /><br />\n\nEste é um e-mail de confirmação nosso.<br />\nVocê estendeu sua assinatura.<br />\n\n<strong>Título do Pacote:</strong> {package_title}<br />\n<strong>Preço do Pacote:</strong> {package_price}<br />\n<strong>Data de Ativação:</strong> {activation_date}<br />\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\nObrigado pela sua compra.</p><p><br />\n\nAtenciosamente,<br />\n{website_title}.<br /></p>'),

(22, 'payment_accepted_for_membership_extension_offline_gateway', 'Seu pagamento para extensão da assinatura foi aceito', '<p>Olá {username},<br /><br />\r\n\r\nEste é um e-mail de confirmação nosso.<br />\r\nSeu pagamento foi aceito e sua assinatura foi estendida.<br />\r\n\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n<strong>Data de Ativação:</strong> {activation_date}<br />\r\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\r\nObrigado pela sua compra.</p><p><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br /></p>'),

(23, 'payment_accepted_for_registration_offline_gateway', 'Seu pagamento para registro foi aprovado', '<p>Olá {username},<br /><br />\r\n\r\nEste é um e-mail de confirmação nosso.<br />\r\nSeu pagamento foi aceito e agora você pode entrar no seu painel de usuário para construir seu site portfólio.<br />\r\n\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n<strong>Data de Ativação:</strong> {activation_date}<br />\r\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\r\nObrigado pela sua compra.</p><p><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br /></p>'),

(24, 'payment_rejected_for_membership_extension_offline_gateway', 'Seu pagamento para extensão da assinatura foi rejeitado', '<p>Olá {username},<br /><br />\r\n\r\nLamentamos informar que seu pagamento foi rejeitado<br />\r\n\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br /></p>'),

(25, 'payment_rejected_for_registration_offline_gateway', 'Seu pagamento para registro foi rejeitado', '<p>Olá {username},<br /><br />\r\n\r\nLamentamos informar que seu pagamento foi rejeitado<br>\r\n\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br /></p>'),

(26, 'admin_changed_current_package', 'Admin alterou seu pacote atual', '<p>Olá {username},<br /><br />\n\nO admin alterou seu pacote atual <b>({replaced_package})</b></p>\n<p><b>Informações do Novo Pacote:</b></p>\n<p>\n<strong>Pacote:</strong> {package_title}<br />\n<strong>Preço do Pacote:</strong> {package_price}<br />\n<strong>Data de Ativação:</strong> {activation_date}<br />\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\nObrigado pela sua compra.</p><p><br />\n\nAtenciosamente,<br />\n{website_title}.<br /></p>'),

(27, 'admin_added_current_package', 'Admin adicionou um pacote atual para você', '<p>Olá {username},<br /><br />\r\n\r\nO admin adicionou um pacote atual para você</p><p><b><span style=\"font-size:18px;\">Informações da Assinatura Atual:</span></b><br />\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n<strong>Data de Ativação:</strong> {activation_date}<br />\r\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\r\nObrigado pela sua compra.</p><p><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br /></p>'),

(28, 'admin_changed_next_package', 'Admin alterou seu próximo pacote', '<p>Olá {username},<br /><br />\n\nO admin alterou seu próximo pacote <b>({replaced_package})</b></p><p><b><span style=\"font-size:18px;\">Informações da Próxima Assinatura:</span></b><br />\n<strong>Título do Pacote:</strong> {package_title}<br />\n<strong>Preço do Pacote:</strong> {package_price}<br />\n<strong>Data de Ativação:</strong> {activation_date}<br />\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\nObrigado pela sua compra.</p><p><br />\n\nAtenciosamente,<br />\n{website_title}.<br /></p>'),

(29, 'admin_added_next_package', 'Admin adicionou um próximo pacote para você', '<p>Olá {username},<br /><br />\r\n\r\nO admin adicionou um próximo pacote para você</p><p><b><span style=\"font-size:18px;\">Informações da Próxima Assinatura:</span></b><br />\r\n<strong>Título do Pacote:</strong> {package_title}<br />\r\n<strong>Preço do Pacote:</strong> {package_price}<br />\r\n<strong>Data de Ativação:</strong> {activation_date}<br />\r\n<strong>Data de Expiração:</strong> {expire_date}</p><p><br /></p><p>Anexamos uma fatura a este e-mail.<br />\r\nObrigado pela sua compra.</p><p><br />\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br /></p>'),

(30, 'admin_removed_current_package', 'Admin removeu seu pacote atual', '<p>Olá {username},<br /><br />\n\nO admin removeu seu pacote atual - <strong>{removed_package_title}</strong><br>\n\nAtenciosamente,<br />\n{website_title}.<br />'),

(31, 'admin_removed_next_package', 'Admin removeu seu próximo pacote', '<p>Olá {username},<br /><br />\r\n\r\nO admin removeu seu próximo pacote - <strong>{removed_package_title}</strong><br>\r\n\r\nAtenciosamente,<br />\r\n{website_title}.<br />');

-- Comando para atualizar registros existentes (alternativa ao INSERT)
/*
UPDATE `email_templates` SET 
    `email_subject` = 'Verifique seu E-mail',
    `email_body` = '<p style="line-height: 1.6;">Olá <b>{customer_name}</b>,</p><p style="line-height: 1.6;"><br>Por favor, clique no link abaixo para verificar seu e-mail.</p><p>{verification_link}</p><p><br></p><p>Atenciosamente,</p><p>{website_title}</p>'
WHERE `id` = 2 AND `email_type` = 'email_verification';

-- Repita para cada registro...
*/