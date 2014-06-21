<div class="well well-small">
    <form method="post" class="form-horizontal">
        <div class="pill-content">
            <div id="confst">
                <div class="widget">
                    <div class="widget-header">
                        <h3>Information de contact</h3>
                    </div>
                    <div class="widget-content">
                        <?php
                        $form = Form::getInstance();
                        echo
                            $form->input('mailSite', 'E-mail du site:', array(
                                'placeholder' => 'noreply@site.url',
                                'value' => clean($config->getSiteMail(), 'str')
                            )) .
                            $form->input('mailContact', 'E-mail de correspondance:', array(
                                'placeholder' => 'contact@site.url',
                                'value' => clean($config->getSiteMailContact(), 'str')
                            ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="active" id="default">
                <div class="widget">
                    <div class="widget-header">
                        <h3>Information sur le site</h3>
                    </div>
                    <div class="widget-content">
                        <?php
                        echo
                            $form->input('siteName', 'Titre:', array(
                                'placeholder' => 'Crystal-Web',
                                'value' => clean($config->getSiteTitle(), 'str')
                            )) .
                            $form->input('siteSlogan', 'Slogan:', array(
                                'placeholder' => 'Et si notre partage faisait l\'&eacute;volution ?',
                                'value' => clean($config->getSiteSlogan(), 'str')
                            )) .
                            $form->input('siteTeamName', 'Equipe:', array(
                                'placeholder' => 'Team Summer Crystal',
                                'value' => clean($config->getSiteTeam(), 'str')
                            ) ) .
                            $form->select('layout', 'Template', $layoutList);
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <?php echo $form->submit('submit', 'Enregistrer'); ?>
    </form>
</div>