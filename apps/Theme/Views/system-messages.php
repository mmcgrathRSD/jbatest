<?php   // Initialise variables.
        $buffer = null;
        $lists = array();
        
        // Get the message queue
        $messages = \Dsc\System::instance()->getMessages();
        
        // Build the sorted message list
        if (is_array($messages) && !empty($messages))
        {
            foreach ($messages as $msg)
            {
                if (isset($msg['type']) && isset($msg['message']))
                {
                    $lists[$msg['type']][] = $msg['message'];
                }
            }
        }
        
        // If messages exist render them
        if (!empty($lists))
        {
            // Build the return string            
            $buffer .= '<div id="system-message-container">';
            foreach ($lists as $type => $msgs)
            {
                $buffer .= "<div id='system-message-" . strtolower($type) . "' class='alert alert-" . strtolower($type) . "'>";
                if (!empty($msgs))
                {
                    $buffer .= "<ul class='list-unstyled'>";
                    foreach ($msgs as $msg)
                    {
                        $buffer .= "<li>" . $msg . "</li>";
                    }
                    $buffer .= "</ul>";
                }
                $buffer .= "</div>";
            }
            $buffer .= "</div>";
        }

        echo $buffer; ?>