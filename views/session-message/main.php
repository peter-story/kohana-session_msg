<?php if (Arr::is_valid_array($messages)) :?>
    <?php foreach ($messages as $type => $msg_array) :?>
        <?php if (Arr::is_valid_array($msg_array)) :?>
            <div class="<?php echo $type?> slide_in slide_out">
                <h6><?php echo ucfirst($type)?>:</h6>
                <?php echo View::factory('common/messages/list', array('msg_array' => $msg_array))?>
            </div>
        <?php endif?>
    <?php endforeach?>
<?php endif?>