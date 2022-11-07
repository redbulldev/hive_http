<?php
/* Smarty version 4.1.1, created on 2022-07-18 12:02:10
  from '/media/hivetech/New Volume/hr-tool/hr-tool-be/templates/index.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d4e95203e223_39975920',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5c56729ef1df7a094b8d9bb072c1252cc3ae8130' => 
    array (
      0 => '/media/hivetech/New Volume/hr-tool/hr-tool-be/templates/index.html',
      1 => 1644821922,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d4e95203e223_39975920 (Smarty_Internal_Template $_smarty_tpl) {
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
