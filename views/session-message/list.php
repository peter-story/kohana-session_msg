<ul>
    <?php foreach ($msg_array as $key => $msg) :?>
        <li>
            <?php if ( ! is_numeric($key) && (strpos($key, '_') === false)) :?>
                <?= $key?>: 
            <?php endif?>
            <?php if (is_string($msg)) :?>
                <?= $msg;?>
            <?php elseif (is_array($msg)) :?>
                <?php if ((count($msg) == 1) && ! is_numeric($k = key($msg)) && (strpos($k, '_') === false)) :?>
                    <?= $k.': '.str_replace($k, '', reset($msg))?>
                 <?php else :?>
                    <?= View::factory('common/messages/list', array('msg_array' => $msg))?>
                <?php endif?>
            <?php endif?>
        </li>
    <?php endforeach?>
</ul>