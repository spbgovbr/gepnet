<?php

/**
 * FlashMessages view helper
 * application/modules/admin/views/helpers/FlashMessages.php
 *
 * This helper creates an easy method to return groupings of
 * flash messages by status.
 *
 * @author Aaron Bach <bachya1208[at]googlemail.com
 * @license Free to use - no strings.
 */
class App_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{

    /**
     * flashMessages function.
     *
     * Takes a specially formatted array of flash messages and prepares them
     * for output.
     *
     * SAMPLE INPUT (in, say, a controller):
     *    $this->_flashMessenger->addMessage(array('message' => 'Success message #1', 'status' => 'success'));
     *    $this->_flashMessenger->addMessage(array('message' => 'Error message #1', 'status' => 'error'));
     *    $this->_flashMessenger->addMessage(array('message' => 'Warning message #1', 'status' => 'warning'));
     *    $this->_flashMessenger->addMessage(array('message' => 'Success message #2', 'status' => 'success'));
     *
     * SAMPLE OUTPUT (in a view):
     *    <div class="success">
     *        <ul>
     *            <li>Success message #1</li>
     *            <li>Success message #2</li>
     *        </ul>
     *    </div>
     *    <div class="error">Error message #1</div>
     *    <div class="warning">Warning message #2</div>
     *
     * @access public
     * @param $translator An optional instance of Zend_Translate
     * @return string HTML of output messages
     */
    private $tituloMessages = array(
        'error' => 'Erro',
        'success' => 'Sucesso',
        'warning' => 'Alerta'
    );

    /*
      private $iconMessages = array(
      'error'    => 'alert',
      'success'  => 'Sucesso',
      'warning'  => 'info'
      );

     */

    public function flashMessages($translator = null)
    {
        //Zend_Session::start();
        // Set up some variables, including the retrieval of all flash messages.
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $statMessages = array();
        $output = '';
        //Zend_Registry::get('firebug')->info($messages);
        // If there are no messages, don't bother with this whole process.
        if (count($messages) > 0) {
            // This chunk of code takes the messages (formatted as in the above sample
            // input) and puts them into an array of the form:
            //    Array(
            //        [status1] => Array(
            //            [0] => "Message 1"
            //            [1] => "Message 2"
            //        ),
            //        [status2] => Array(
            //            [0] => "Message 1"
            //            [1] => "Message 2"
            //        )
            //        ....
            //    )
            foreach ($messages as $message) {
                if (!array_key_exists($message['status'], $statMessages)) {
                    $statMessages[$message['status']] = array();
                }

                if ($translator != null && $translator instanceof Zend_Translate) {
                    array_push($statMessages[$message['status']], $translator->_($message['message']));
                } else {
                    array_push($statMessages[$message['status']], $message['message']);
                }
            }

            // This chunk of code formats messages for HTML output (per
            // the example in the class comments).
            //Zend_Debug::dump($statMessages);

            foreach ($statMessages as $status => $messages) {
                $output .= '<div class="alert alert-' . $status . ' ' . $status . '">';

                // If there is only one message to look at, we don't need to deal with
                // ul or li - just output the message into the div.
                if (count($messages) == 1) {
                    $msn = (is_array($messages[0])) ? $messages[0][0] : $messages[0];
                    $output .= '<strong>' . $this->tituloMessages[$status] . ': </strong>' . $msn;
                }

                // If there are more than one message, format it in the fashion of the
                // sample output above.
                else {
                    $output .= '<strong>' . $this->tituloMessages[$status] . ': </strong>';
                    $output .= '<ul>';
                    foreach ($messages as $message) {
                        $output .= '<li>' . $message . '</li>';
                    }
                    $output .= '</ul>';
                    //$output .= '</p>';
                }

                $output .= '</div>';
            }
            /*
              foreach ($statMessages as $status => $messages)
              {
              $output .= '<div class="ui-widget"><div class="ui-state-' . $status . ' ui-corner-all" style="padding: 5px 10px; margin: 20px 0;overflow:hidden;">';

              // If there is only one message to look at, we don't need to deal with
              // ul or li - just output the message into the div.
              if (count($messages) == 1) {
              $msn = (is_array($messages[0])) ? $messages[0][0] :$messages[0];
              $output .=  '<p><span class="ui-icon ui-icon-' . $this->iconMessages[$status] .'" style="float: left; margin-right: 0.3em;"></span><strong>' . $this->tituloMessages[$status] . ': </strong>' . $msn . '</p>';
              }

              // If there are more than one message, format it in the fashion of the
              // sample output above.

              else
              {
              $output .=  '<p><span class="ui-icon ui-icon-' . $this->iconMessages[$status] .'" style="float: left; margin-right: 0.3em;"></span><strong>' . $this->tituloMessages[$status] . ': </strong>';
              $output .= '<ul>';
              foreach ($messages as $message) {
              $output .= '<li>' . $message . '</li>';
              }
              $output .= '</ul>';
              $output .= '</p>';
              }

              $output .= '</div></div>';
              }

             */

            // Return the final HTML string to use.
            return $output;
        }
    }

}