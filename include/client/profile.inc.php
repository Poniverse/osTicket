<h1><?php echo __('Manage Your Profile Information'); ?></h1>
<p><?php echo __(
'Use the forms below to update the information we have on file for your account'
); ?>
</p>
<form action="profile.php" method="post">
  <?php csrf_token(); ?>
<table width="800" class="padded">
<?php
foreach ($user->getForms() as $f) {
    $f->render(false);
}
if ($acct = $thisclient->getAccount()) {
    $info=$acct->getInfo();
    $info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<tr>
    <td colspan="2">
        <div><hr><h3><?php echo __('Preferences'); ?></h3>
        </div>
    </td>
</tr>
    <tr>
        <td width="180">
            <?php echo __('Time Zone');?>:
        </td>
        <td>
            <?php
            $TZ_NAME = 'timezone';
            $TZ_TIMEZONE = $info['timezone'];
            include INCLUDE_DIR.'staff/templates/timezone.tmpl.php'; ?>
            <div class="error"><?php echo $errors['timezone']; ?></div>
        </td>
    </tr>
<?php if ($cfg->getSecondaryLanguages()) { ?>
    <tr>
        <td width="180">
            <?php echo __('Preferred Language'); ?>:
        </td>
        <td>
    <?php
    $langs = Internationalization::getConfiguredSystemLanguages(); ?>
            <select name="lang">
                <option value="">&mdash; <?php echo __('Use Browser Preference'); ?> &mdash;</option>
<?php foreach($langs as $l) {
$selected = ($info['lang'] == $l['code']) ? 'selected="selected"' : ''; ?>
                <option value="<?php echo $l['code']; ?>" <?php echo $selected;
                    ?>><?php echo Internationalization::getLanguageDescription($l['code']); ?></option>
<?php } ?>
            </select>
            <span class="error">&nbsp;<?php echo $errors['lang']; ?></span>
        </td>
    </tr>
<?php }
      if ($acct->isPasswdResetEnabled()) { ?>
<?php } ?>
<?php } ?>
</table>
<hr>
<p style="text-align: center;">
    <input type="submit" value="Update"/>
    <input type="reset" value="Reset"/>
    <input type="button" value="Cancel" onclick="javascript:
        window.location.href='index.php';"/>
</p>
</form>
