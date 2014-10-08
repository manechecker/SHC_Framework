<?php

namespace RWF\Template\Plugin;

//Imports
use RWF\Template\TemplateCompilerPlugin;
use RWF\Template\TemplateCompiler;
use RWF\Template\Exception\TemplateCompilationException;
use RWF\Util\String;

/**
 * erzeugt einen bearbeiten Button
 *
 * @author     Oliver Kleditzsch
 * @copyright  Copyright (c) 2014, Oliver Kleditzsch
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since      2.0.0-0
 * @version    2.0.0-0
 */
class EditLinkCompilerPlugin implements TemplateCompilerPlugin {

    /**
     * wird beim Compilieren eines unbekannten Tags ausgefuehrt
     *
     * @param  Array                          $args     Argumente
     * @param  \RWF\Template\TemplateCompiler $compiler Compiler Objekt
     * @return String
     */
    public function execute(array $args, TemplateCompiler $compiler) {

        //Plichtangabe Pruefen
        if(!isset($args['link'])) {

            throw new TemplateCompilationException('missing "link" attribute in premission tag', $compiler->getTemplateName(), $compiler->getCurrentLine());
        }

        if(!isset($args['id'])) {

            throw new TemplateCompilationException('missing "id" attribute in premission tag', $compiler->getTemplateName(), $compiler->getCurrentLine());
        }

        $randomStr = String::randomStr(64);
        $link = str_replace('\'', '', $args['link']) .'<?php echo '. $args['id'] .'; ?>';
        $html = '<a href="#"  id="'. $randomStr .'" class="shc-view-buttons-edit" title="<?php echo \\RWF\\Core\\RWF::getLanguage()->get(\'global.button.edit\'); ?>"></a>';
        $html .= '<script type="text/javascript">';
        $html .= '$(function() {';
        $html .= '  $(\'.shc-view-buttons-edit\').tooltip({';
        $html .= '      track: false,';
        $html .= '      position: {';
        $html .= '          my: "left+15 center",';
        $html .= '          at: "right center"';
        $html .= '      }';
        $html .= '  });';
        $html .= '  $(\'#'. $randomStr .'\').click(function() {';
        $html .= '      $.get(\''. $link .'\', function(data, textStatus, jqXHR) {;';
        $html .= '          $(\'#shc-view-acp-contentBox div.shc-contentbox-body\').html(data);';
        $html .= '      });';
        $html .= '  });';
        $html .= '});';
        $html .= '</script>';
        return $html;
    }
}
