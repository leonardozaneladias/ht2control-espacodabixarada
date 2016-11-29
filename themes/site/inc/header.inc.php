<header class="main-header">
    <div class="container">
        <hgroup>
            <h1>Cidade Online - Eventos, Promoções e Novidades!</h1>
            <h2>Confira os eventos, promoções e novidades em sua cidade. Aqui, no Cidade Online!</h2>
        </hgroup>

        <div class="header-banner">
            <!--468x60-->
            <a href="http://www.upinside.com.br/campus" title="Campus UpInside - Cursos Profissionais em TI">
                <img src="<?= INCLUDE_PATH; ?>/_tmp/banner_medium.png" title="Campus UpInside - Cursos Profissionais em TI" alt="Campus UpInside - Cursos Profissionais em TI" />
            </a>
        </div><!-- banner -->

        <nav class="main-nav">

            <ul class="top">
                <li><a href="<?= HOME ?>" title="">Home</a></li>
                <li><a href="<?= HOME ?>/categoria/noticias" title="">Formatura</a>
                    <ul class="sub">
                        <li><a href="<?= HOME ?>/categoria/aconteceu" title="">Adesão</a></li>
                        <li><a href="<?= HOME ?>/categoria/eventos" title="">Contrato</a></li>
                    </ul>                
                </li>
                <li><a href="<?= HOME ?>/empresas/onde-comer" title="">Notícias</a></li>
                <li><a href="<?= HOME ?>/empresas/onde-ficar" title="">Eventos</a></li>
                <li><a href="<?= HOME ?>/empresas/onde-ficar" title="">FAQ</a></li>
                <li><a href="<?= HOME ?>/empresas/onde-comprar" title="">Contato</a></li>

                <li class="search">
                    <?php
                    $search = filter_input(INPUT_POST, 's', FILTER_DEFAULT);
                    if (!empty($search)):
                        $search = strip_tags(trim(urlencode($search)));
                        header('Location: ' . HOME . '/pesquisa/' . $search);
                    endif;
                    ?>

                    <form name="search" action="" method="post">
                        <input class="fls" type="text" name="s" />
                        <input class="btn" type="submit" name="sendsearch" value="" />
                    </form>
                </li>

            </ul>
        </nav> <!-- main nav -->

    </div><!-- Container Header -->
</header> <!-- main header -->