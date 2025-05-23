<script type="text/javascript">
    function change_button() {
        d = document.getElementById("frecuency").value;
        if (d > 0){
            document.getElementById("premium2_button").value = 'SUBIR';
            document.getElementById("night").disabled = false;
        }
        else{
            document.getElementById("premium2_button").value = 'DESACTIVAR';
            document.getElementById("night").disabled = true;
        }
    }
</script>

<?
if (isset($_GET['i'])) {
    
    $ad = getDataAd($_GET['i']);
    if (count($ad['ad']) != 0) {
        if (count($ad['images']) != 0) $image = true;

        $adult = 0;
        $name = $ad['parent_cat']['name'];
        if ($name == 'Contactos') $adult = 1;

        	$paypal_configuration=selectSQL("sc_paypal",$w=array('ID_paypal'=>1));
        	$paypal_currency_codes=selectSQL("sc_currency_code",$w=array('ID_currency'=>$paypal_configuration[0]['ID_currency_code']));

        // 	console_log($_GET);

        if (isset($_POST['credits']) && isset($_POST['frecuency']) ) {
            if (isset($_POST['night'])) 
                $night = $_POST['night'];
            else 
                $night = 0;
            
            set_premium_2($ad, $_POST['frecuency'], $night);
            
            $ad = getDataAd($_GET['i']);
            
            // console_log($_POST);
            // header("Location: " . urlAd($ad['ad']['ID_ad']));
            header("Location: ". getConfParam('SITE_URL')  . $ad['parent_cat']['name_seo'] . '/' .$ad['category']['name_seo']  );
            exit;
        }

        $user = selectSQL('sc_user', $w = array('ID_user' => 1));
        
        if (isset($_POST))
            console_log($_POST['banner_img']);
        ?>

        <div class="col_single">
            <div class="info_item_up">
                <div class="info_item_img" style="background: url('<?= IMG_ADS; ?><? if ($image) { ?><?= $ad['images'][0]['name_image']; ?><? } else { echo IMG_AD_DEFAULT; } ?>');">
                
                </div>
                <div class="info_item_text">
                    <h2><?= stripslashes(ucfirst($ad['ad']['title'])) ?></h2>
                    
                    <p <?= empty($_GET['new']) ? 'style="visibility: hidden"' : ''; ?> ><?= $language['premium.info_subtitle'] ?></p>
                    
                    
                </div>
                <a href="<?= getConfParam('SITE_URL') ?>mis-anuncios/" class="show-ad">Ver anuncio</a>
                <h1>TE OFRECEMOS LAS SEGUINTES OPCIONES PARA DESTACAR TU ANUNCIO Y AUMENTAR TUS VENTAS</h1>
                
                
            </div>
            
            <div class="box_container">
                <!------------------------------------------------------------------------------------------------------------------------------------->
                
                <div class="box_premium listing">
                    <h3><?= $language['premium.premium_2_title'] ?></h3>
                    <div class="box_premium_p">
                        <div class="box_premium_img"><img src="src/images/premium2_example.png" alt="Destacar anuncio" /></div>
                        <div class="info listing"><?= $language['premium.premium_2_info'] ?></div>
                        <form method="post" action="<? $_SERVER['PHP_SELF']; ?>">
        
                            <?
                                if ($adult) {
                                    $credits = $ad['user']['credits_adult'];
                                } else {
                                    $credits = $ad['user']['credits'];
                                }
                            ?>
        
                            <div class="box_credits">
                                <input type="hidden" name="credits" value="<?= $credits ?>">
                                <div class="credit_text">
                                    <?= $language['credit.label'] ?>
                                </div><!--
                             --><div class="credit_number" name="credits">
                                    <?= $credits ?>
                                </div><img src="src/images/credits_coins.png" alt="credits" class="coin_img">
                            </div>
                            
                            <div class="box_select">
                                <label for="frecuency">Subir</label>
                                <select class="select" name="frecuency" id="frecuency" onchange="change_button()">
                                    <?
                                            // $ad = getDataAd($_GET['i']);
                                            // console_log('ad 2');
                                            // console_log($ad['ad']);
                                            
                                            if ($ad['ad']['premium2'] == 1) { ?>
                                        <option <? if ($ad['ad']['premium2_frecuency'] == 0) echo 'selected'; ?> value="0">Desactivado</option>
                                    <? } ?>
                                    <?
                                            $plan_times = selectSQL("sc_time_options", $a = array('owner' => 'LISTING'));
                                            for ($i = 0; $i < count($plan_times); $i++) { ?>
        
                                        <option 
                                            <? if ($ad['ad']['premium2_frecuency'] === $plan_times[$i]['value']) echo 'selected'; ?> 
                                            value="<?= $plan_times[$i]['value'] ?>">Cada <?= $plan_times[$i]['quantity'] == 1 ? '' : $plan_times[$i]['quantity']; ?> <?= $plan_times[$i]['time_unit'] ?></option>
                                    <? } ?>
        
                                </select>
                                <label for="night">Noche</label>
                                <select class="select" name="night" id="night">
                                    <option value="0" selected>No</option>
                                    <option value="1" <? if ($ad['ad']['premium2_night'] == 1) echo 'selected'; ?>>Si</option>
                                </select>
                            </div>
        
                            <!--<label><?= print_r($ad) ?></label>-->
        
                            <div class="box_pay">
                                
                                <input type="submit" name="submit" class="pay" id="premium2_button" value="SUBIR" <?if ($credits <= 0) echo 'disabled';?>>
                                <!--<div class="pay" id="paypal_2"><?= $language['premium.premium_2_button'] ?></div>-->
                                <div class="buy_credit_text">
                                    <a href="<?=$urlfriendly['url.credits']?><?= $adult == 1 ? '1' : '0'; ?>">
                                        <?= $language['premium.buy_credits'] ?><img src="src/images/coins_bottom.png" alt="credits" width="40px">
                                    </a>
                                </div>
                            </div>
        
                        </form>
                    </div>
            </div>
                
                <!------------------------------------------------------------------------------------------------------------------------------------->
                
                <div class="box_premium">
                    <h3><?= $language['premium.premium_1_title'] ?></h3>
                    <div class="box_premium_p">
                        <div class="box_premium_img"><img src="src/images/premium1_example.png" alt="<?= $language['premium.premium_1_title'] ?>" /></div>
                        <div class="info premium"><?= $language['premium.premium_1_info'] ?><?= $language['premium.premium_times'] ?></div>
                        <div class="selector_premium">
                            <label><?= $language['premium.duration_text'] ?></label>
                            <select class="select" name="time_plan" id="premium1_select">
                                <option selected value="0">Selecciona</option>
                                <?
                                    $plan_times = selectSQL("sc_time_options", $a = array('owner' => 'PREMIUM'));
                                    for ($i = 0; $i < count($plan_times); $i++) {
                                        ?>
                                        <?if ($adult)
                                            $price = $plan_times[$i]['price_adult'];
                                        else
                                            $price = $plan_times[$i]['price'];
                                        ?>
                                        <option value="<?= $plan_times[$i]['value'] ?>">
                                            <?= $plan_times[$i]['quantity'] ?> <?= $plan_times[$i]['time_unit'] ?>
                                            <?=$price;?>
        
                                            <?= $paypal_currency_codes[0]['code_symbol']; ?>
                                        </option>
                                <? } ?>
                            </select>
                        </div>
                    
                        <div class="box_pay">
                            <input type="submit" name="submit" class="pay" id="paypal_1" value="<?= $language['premium.premium_1_button'] ?>">
                            <div class="pay_cards">
                                <img src="<?= IMG_PATH ?>card-logo-visa.svg" alt="visa">
                                <img src="<?= IMG_PATH ?>card-logo-mastercard.svg" alt="mastercard">
                                <img src="<?= IMG_PATH ?>card-logo-amex.svg" alt="amex">
                            </div>
                        </div>
                    </div>
            </div>
                
                <!------------------------------------------------------------------------------------------------------------------------------------->
                
                <div class="box_premium banner">
                    <h3><?= $language['premium.banner_title'] ?></h3>
                    <div class="box_premium_p">
                        <div class="box_premium_img banner_img"><img src="src/images/banner_example.png" alt="Destacar anuncio" /></div>
                        <div class="info banner"><?= $language['premium.banner_info'] ?>
                        </div>
                        <div class="selector_banner">
                            <label><?= $language['premium.banner_zone_duration'] ?></label>
                            <select name="banner_select" required form="paypal_BAN" id="banner_selector">
                                <option selected value="0">Selecciona</option>
                                <?
                                        $plan_times = selectSQL("sc_time_options", $a = array('owner' => 'BANNER'));
                                        for ($i = 0; $i < count($plan_times); $i++) {
                                            ?>
                                    <option value="<?= $plan_times[$i]['value'] ?>">
                                        <?= $plan_times[$i]['position'] ?> <?= $plan_times[$i]['quantity'] ?> <?= $plan_times[$i]['time_unit'] ?>
                                        <?
                                                    if ($adult)
                                                        $price = (int) $plan_times[$i]['price_adult'];
                                                    else
                                                        $price = (int) $plan_times[$i]['price'];
                                                    echo $price;
                                                    ?>
        
                                        <?= $paypal_currency_codes[0]['code_symbol']; ?>
                                    </option>
                                <? } ?>
                            </select>
                        </div>
                        <div class="banner_image">
                            <label for="img_input"><?= $language['premium.banner_upload_banner'] ?></label>
                            <label for="banner_img" class="input_box" id="img_input">
                                Seleccionar imagen
                            </label>
                            <input type="file" id="banner_img" name="banner_img" accept=".png, .jpg, .jpeg, .gif" required form="paypal_BAN">
                        </div>
                            
                        <div class="banner_link">
                            <label for="link_input"><?= $language['premium.banner_upload_link'] ?></label>
                            <input type="text" name="banner_link" id="link_input">
                        </div>
        
                        <div class="box_pay">
                            <input type="submit" name="submit" class="pay" id="paypal_2" value="<?= $language['premium.premium_1_button'] ?>">
                            <div class="pay_cards">
                                <img src="<?= IMG_PATH ?>card-logo-visa.svg" alt="visa">
                                <img src="<?= IMG_PATH ?>card-logo-mastercard.svg" alt="mastercard">
                                <img src="<?= IMG_PATH ?>card-logo-amex.svg" alt="amex">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <? }
    } ?>
<?
    //     $ad = getDataAd($_GET['i']);
    //     console_log('final');
    //     console_log($ad['ad']);
    
?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_PREM">
<input type='hidden' name='cmd' value='_xclick'>
<input type='hidden' name='business' value='<?= $paypal_configuration[0]['paypal_email']; ?>'>
<input type='hidden' name='item_name' value='<?= $language['premium.form_premium1'] ?> - <?= substr($ad['ad']['title'], 0, 20) ?>.. - <?= $ad['ad']['ID_ad'] ?>'>
<input type="hidden" name="return"  value="<?= getConfParam('SITE_URL'); ?>">
<input type="hidden" name="notify_url"  value="<?= getConfParam('SITE_URL'); ?>sc-includes/php/paypal/paypal_1.php?id=<?= $ad['ad']['ID_ad'] ?>">
<input type="hidden" name="item_numer"  value="<?= $ad['ad']['ID_ad'] ?>">
<input type='hidden' name='amount' value='<?= $paypal_configuration[0]['price_1']; ?>'>
<input type='hidden' name='page_style' value='primary'>
<input type="hidden" name="landing_page" value="Billing" />
<input type='hidden' name='no_shipping' value='1'>
<input type='hidden' name='no_note' value='1'>
<input type='hidden' name='currency_code' value='<?= $paypal_currency_codes[0]['currency_code']; ?>'>
<input type='hidden' name='cn' value='PP-BuyNowBF'>
<input type='hidden' name='lc' value='<?= COUNTRY_NAME; ?>'>
<input type='hidden' name='country' value='<?= COUNTRY_NAME; ?>'>
</form>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_BAN">
<input type='hidden' name='cmd' value='_xclick'>
<input type='hidden' name='business' value='<?= $paypal_configuration[0]['paypal_email']; ?>'>
<input type='hidden' name='item_name' value='<?= $language['premium.form_premium2'] ?> - <?= substr($ad['ad']['title'], 0, 20) ?>.. - <?= $ad['ad']['ID_ad'] ?>'>
<input type="hidden" name="return" value="<?= getConfParam('SITE_URL'); ?>">
<input type="hidden" name="notify_url" value="<?= getConfParam('SITE_URL'); ?>sc-includes/php/paypal/paypal_2.php?id=<?= $ad['ad']['ID_ad'] ?>">
<input type="hidden" name="item_numer" value="<?= $ad['ad']['ID_ad'] ?>">
<input type="hidden" name="landing_page" value="Billing" />
<input type='hidden' name='amount' value='<?= $paypal_configuration[0]['price_2']; ?>'>
<input type='hidden' name='page_style' value='primary'>
<input type='hidden' name='no_shipping' value='1'>
<input type='hidden' name='no_note' value='1'>
<input type='hidden' name='currency_code' value='<?= $paypal_currency_codes[0]['currency_code']; ?>'>
<input type='hidden' name='cn' value='PP-BuyNowBF'>
<input type='hidden' name='lc' value='<?= COUNTRY_NAME; ?>'>
<input type='hidden' name='country' value='<?= COUNTRY_NAME; ?>'>
</form>

<script>
    $('#banner_img').change(function() {
        var i = $(this).prev('label').clone();
        var file = $('#banner_img')[0].files[0].name;
        $(this).prev('label').text(file);
        
        var file = document.getElementById('banner_img').files[0];
        var filesize = file.size;
        console.log(file);
        if (filesize > 3000000){
            alert('Tamaño máximo de imagen es de 3MB');
            $('#banner_img').val('');
            $(this).prev('label').text('Seleccionar imagen');
        }    
        console.log(document.getElementById('banner_img').files[0]);
    });
    
    
</script>

