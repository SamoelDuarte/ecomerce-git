<?php
echo "<h1>Teste de Configuração do Apache</h1>";
echo "<h2>Módulos carregados:</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p style='color: green;'>✓ mod_rewrite está HABILITADO</p>";
    } else {
        echo "<p style='color: red;'>✗ mod_rewrite NÃO está habilitado</p>";
    }
    echo "<ul>";
    foreach ($modules as $module) {
        echo "<li>$module</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Função apache_get_modules() não disponível</p>";
}

echo "<h2>Informações do PHP:</h2>";
echo "<p>Versão do PHP: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "</p>";
?>