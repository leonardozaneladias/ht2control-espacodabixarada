<footer class="main-footer" id="contato">
    <section class="container">                
        <nav>
            <h3 class="line_title"><span>Rede Social:</span></h3>
            <ul>
                <li><a href="http://www.upinside.com.br/campus" title="Home">Facebook</a></li>
                <li><a href="<?= HOME ?>/cadastra-empresa" title="Home">Twitter</a></li>
                <li><a href="http://www.facebook.com/upinside" target="_blank" title="Home">Instagram</a></li>
                <li><a href="<?= HOME ?>" title="Home">Google+</a></li>
            </ul>
        </nav>

        <section>
            <h3 class="line_title"><span>Sobre a Comissão:</span></h3>
            <p>Tempor porta mus odio egestas dolor est rhoncus hac cursus, dignissim nunc urna aliquet, tempor penatibus odio dapibus tincidunt? Natoque, aliquet, vut, elementum et? Adipiscing cursus dapibus risus purus? Lundium massa mid! Sociis lorem, adipiscing dolor?</p>
            <p><a href="#" title="SiForme">Clique aqui e saiba mais!</a></p>
        </section>

        <section class="footer_contact">
            <h3 class="line_title"><span>Contato:</span></h3>
            
            <?php
            $Contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if($Contato && $Contato['SendFormContato']):
                unset($Contato['SendFormContato']);
            
                $Contato['Assunto'] = 'Mensagem via Site!';
                $Contato['DestinoNome'] = 'Robson V. Leite - UPINSIDE';
                $Contato['DestinoEmail'] = 'sistema@upinside.com.br';
                
                $SendMail = new Email;
                $SendMail->Enviar($Contato);
                
                if($SendMail->getError()):
                    WSErro($SendMail->getError()[0], $SendMail->getError()[1]);
                endif;
                
            endif;
            ?>
            
            <form name="FormContato" action="#contato" method="post">
                <label>
                    <span>nome:</span>
                    <input type="text" title="Informe seu nome" name="RemetenteNome" required />
                </label>

                <label>
                    <span>e-mail:</span>
                    <input type="email" title="Informe seu e-mail" name="RemetenteEmail" required />
                </label>

                <label>
                    <span>mensagem:</span>
                    <textarea title="Envie sua mensagem" name="Mensagem" required rows="3"></textarea>
                </label>

                <input type="submit" value="Enviar" name="SendFormContato" class="btn">                        
            </form>
        </section>
        <div class="clear"></div>
    </section><!-- /ontainer -->
    <!--<div class="footer_logo">SiForme Site da Turma</div><!-- footer logo -->
</footer>