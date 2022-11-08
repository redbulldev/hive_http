<?php
/* Smarty version 4.1.1, created on 2022-11-08 08:56:27
  from '/home/amidn/hive_http/_temp/hr-tool-dev-me/templates/index.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_6369b74b076652_98650923',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0ffdace49ed08328f137537b47ece5007dc2d018' => 
    array (
      0 => '/home/amidn/hive_http/_temp/hr-tool-dev-me/templates/index.html',
      1 => 1667458787,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6369b74b076652_98650923 (Smarty_Internal_Template $_smarty_tpl) {
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
