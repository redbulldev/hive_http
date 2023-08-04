<?php
/* Smarty version 4.1.0, created on 2022-06-05 21:29:03
  from '/Volumes/DATA/micro/hiro/backend/booking-lunch/templates/index.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_629cbdaf5e9e10_91410951',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3f852e7580a8ee6a460b3d6e70d580a0b8541dba' => 
    array (
      0 => '/Volumes/DATA/micro/hiro/backend/booking-lunch/templates/index.html',
      1 => 1654422706,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_629cbdaf5e9e10_91410951 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="./templates/swagger-ui.css" >
    <link rel="icon" type="image/png" href="./templates/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="./templates/favicon-16x16.png" sizes="16x16" />
    <style>
      html
      {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }

      *,
      *:before,
      *:after
      {
        box-sizing: inherit;
      }

      body
      {
        margin:0;
        background: #fafafa;
      }
    </style>
  </head>

  <body>
    <div id="swagger-ui"></div>

    <?php echo '<script'; ?>
 src="./templates/swagger-ui-bundle.js" charset="UTF-8"> <?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="./templates/swagger-ui-standalone-preset.js" charset="UTF-8"> <?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
    window.onload = function() {
      // Begin Swagger UI call region
      const ui = SwaggerUIBundle({
        url: "./templates/swagger.json",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        plugins: [
          SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "StandaloneLayout"
      })
      // End Swagger UI call region

      window.ui = ui
    }
  <?php echo '</script'; ?>
>
  </body>
</html>
<?php }
}
