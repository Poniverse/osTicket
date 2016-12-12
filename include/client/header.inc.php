<?php
$title=($cfg && is_object($cfg) && $cfg->getTitle())
    ? $cfg->getTitle() : 'osTicket :: '.__('Support Ticket System');
$signin_url = ROOT_PATH . "login.php"
    . ($thisclient ? "?e=".urlencode($thisclient->getEmail()) : "");
$signout_url = ROOT_PATH . "logout.php?auth=".$ost->getLinkToken();

header("Content-Type: text/html; charset=UTF-8");
if (($lang = Internationalization::getCurrentLanguage())) {
    $langs = array_unique(array($lang, $cfg->getPrimaryLanguage()));
    $langs = Internationalization::rfc1766($langs);
    header("Content-Language: ".implode(', ', $langs));
}
?>
<!DOCTYPE html>
<html<?php
if ($lang
        && ($info = Internationalization::getLanguageInfo($lang))
        && (@$info['direction'] == 'rtl'))
    echo ' dir="rtl" class="rtl"';
if ($lang) {
    echo ' lang="' . $lang . '"';
}
?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo Format::htmlchars($title); ?></title>
    <meta name="description" content="customer support platform">
    <meta name="keywords" content="osTicket, Customer support system, support ticket system">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/osticket.css" media="screen">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/theme.css" media="screen">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/print.css" media="print">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>scp/css/typeahead.css"
         media="screen" />
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css"
        rel="stylesheet" media="screen" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/thread.css" media="screen">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/redactor.css" media="screen">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/flags.css">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/rtl.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/select2.min.css">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/poniverse.css" media="screen">
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>js/osticket.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/filedrop.field.js"></script>
    <script src="<?php echo ROOT_PATH; ?>scp/js/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-plugins.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-osticket.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/fabric.min.js"></script>
    <?php
    if($ost && ($headers=$ost->getExtraHeaders())) {
        echo "\n\t".implode("\n\t", $headers)."\n";
    }

    // Offer alternate links for search engines
    // @see https://support.google.com/webmasters/answer/189077?hl=en
    if (($all_langs = Internationalization::getConfiguredSystemLanguages())
        && (count($all_langs) > 1)
    ) {
        $langs = Internationalization::rfc1766(array_keys($all_langs));
        $qs = array();
        parse_str($_SERVER['QUERY_STRING'], $qs);
        foreach ($langs as $L) {
            $qs['lang'] = $L; ?>
        <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>?<?php
            echo http_build_query($qs); ?>" hreflang="<?php echo $L; ?>" />
<?php
        } ?>
        <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
            hreflang="x-default" />
<?php
    }
    ?>
</head>
<body>
    <div id="header">
        <div id="container">
            <div class="pull-right flush-right">
                <ul>
                    <?php
                    if ($thisclient && is_object($thisclient) && $thisclient->isValid()
                        && !$thisclient->isGuest()) {
                        echo Format::htmlchars($thisclient->getName()).'&nbsp;|';
                        ?>
                        <li><a href="<?php echo ROOT_PATH; ?>profile.php"><?php echo __('Profile'); ?></a></li>
                        <li><a href="<?php echo ROOT_PATH; ?>tickets.php"><?php echo sprintf(__('Tickets <b>(%d)</b>'), $thisclient->getNumTickets()); ?></a></li>
                        <li><a href="<?php echo $signout_url; ?>"><?php echo __('Sign Out'); ?></a></li>
                        <?php
                    } elseif($nav) {
                        if ($thisclient && $thisclient->isValid() && $thisclient->isGuest()) { ?>
                            <li><a href="<?php echo $signout_url; ?>" class="poni-sign-in poni-button"><?php echo __('Sign Out'); ?></a></li><?php
                        }
                        elseif ($cfg->getClientRegistrationMode() != 'disabled') { ?>
                            <li><a href="<?php echo $signin_url; ?>" class="poni-sign-in poni-button"><?php echo __('Sign In'); ?></a></li>
                            <?php
                        }
                    } ?>

                    <?php
                    if (($all_langs = Internationalization::getConfiguredSystemLanguages())
                        && (count($all_langs) > 1)
                    ) {
                        $qs = array();
                        parse_str($_SERVER['QUERY_STRING'], $qs);
                        foreach ($all_langs as $code=>$info) {
                            list($lang, $locale) = explode('_', $code);
                            $qs['lang'] = $code;
                            ?>
                            <a class="flag flag-<?php echo strtolower($locale ?: $info['flag'] ?: $lang); ?>"
                               href="?<?php echo http_build_query($qs);
                               ?>" title="<?php echo Internationalization::getLanguageDescription($code); ?>">&nbsp;</a>
                        <?php }
                    } ?>
                </ul>
            </div>
            <a class="pull-left" id="logo" href="<?php echo ROOT_PATH; ?>index.php"
               title="<?php echo __('Poniverse Support'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 203.808 133.629"><g fill="#fff"><path d="M108.92 27.247C94.127 12.455 73.722 3.275 51.172 3.175h-.744c-10.25.074-19.777 3.183-27.73 8.473C9.114 20.685.127 36.088.002 53.6v.744c.095 21.394 8.365 40.857 21.845 55.43.002 0 .002 0 .002.002 2.78 3.003 7.47 3.185 10.473.407 2.197-2.03 2.885-5.082 2.013-7.75l-.1-.282c-.02-.054-.04-.11-.063-.165 0 0 0 .002.01.003-1.802-5.208-2.782-10.8-2.782-16.623 0-1.05.032-2.093.095-3.127 0-.037 0-.073.01-.11 0-.08.01-.16.017-.24.747-9.81 8.688-17.396 18.307-17.9.75-.042 1.51-.038 2.28.01 3.53.227 6.778 1.383 9.522 3.213v.005c1.568 1.067 3.362 1.74 5.222 1.98.105.015.21.027.316.038 11.695 1.345 23.767-1.367 33.938-8.137 2.693-1.79 5.253-3.868 7.637-6.23l.18-.177.175-.18c2.463-2.525 4.1-5.555 4.91-8.752 1.628-6.412-.067-13.493-5.085-18.51zM94.278 104.678c14.793 14.792 35.198 23.972 57.748 24.072h.744c10.25-.074 19.777-3.183 27.73-8.473 13.585-9.037 22.57-24.44 22.697-41.952v-.372-.372c-.094-21.393-8.364-40.856-21.844-55.428l-.002-.003c-2.78-3.004-7.468-3.186-10.472-.408-2.197 2.03-2.885 5.082-2.013 7.75l.1.282c.02.055.04.11.063.166 0 0 0-.002-.01-.003 1.802 5.208 2.782 10.8 2.782 16.623 0 1.05-.03 2.093-.094 3.127 0 .037 0 .073-.01.11-.01.08-.01.16-.017.24-.748 9.81-8.69 17.396-18.308 17.9-.75.042-1.51.038-2.28-.01-3.53-.227-6.778-1.383-9.522-3.213v-.004c-1.57-1.067-3.363-1.74-5.223-1.98-.105-.015-.21-.027-.316-.038-11.694-1.345-23.766 1.367-33.937 8.137-2.693 1.79-5.253 3.867-7.637 6.23l-.18.176-.175.18c-2.462 2.524-4.1 5.554-4.91 8.75-1.628 6.413.067 13.494 5.085 18.512z"></path></g></svg>
            </a>

            <div class="clear"></div>

            <div id="poni-subheading">
                <h1>Support</h1>
            </div>
        </div>
    </div>

    <div id="container">
        <div class="clear"></div>
        <?php
        if($nav){ ?>
        <ul id="nav" class="flush-left">
            <?php
            if($nav && ($navs=$nav->getNavLinks()) && is_array($navs)){
                foreach($navs as $name =>$nav) {
                    echo sprintf('<li><a class="%s %s" href="%s">%s</a></li>%s',$nav['active']?'active':'',$name,(ROOT_PATH.$nav['href']),$nav['desc'],"\n");
                }
            } ?>
        </ul>
        <?php
        }else{ ?>
         <hr>
        <?php
        } ?>
        <div id="content">

         <?php if($errors['err']) { ?>
            <div id="msg_error"><?php echo $errors['err']; ?></div>
         <?php }elseif($msg) { ?>
            <div id="msg_notice"><?php echo $msg; ?></div>
         <?php }elseif($warn) { ?>
            <div id="msg_warning"><?php echo $warn; ?></div>
         <?php } ?>
