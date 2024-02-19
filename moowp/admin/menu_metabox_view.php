<div id="moosocial-links" class="posttypediv">
    <div id="tabs-panel-login-links-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
        <ul id="moosocial-linkschecklist" class="list:moosocial-links categorychecklist form-no-clear">
            <?php if(empty($elems_obj)): ?>
                <?php esc_attr_e('No items.', 'moowp'); ?>
            <?php else: ?>
                <?php echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $elems_obj), 0, (object) array('walker' => $walker)); ?>
            <?php endif; ?>
        </ul>
    </div>
    <?php if($this->moosocial_is_connecting == 1 && empty($this->moosocial_pages_menu)): ?>
    <p class="button-controls">
      <span class="add-to-menu">
          <a class="button-secondary right" href="?moosocialmenu=load"><?php esc_attr_e('Load Menu', 'moowp'); ?></a>
      </span>
    </p>
    <?php else: ?>
    <p class="button-controls">
      <span class="add-to-menu" style="position: relative;">
        <input type="submit"<?php disabled($nav_menu_selected_id, 0); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu', 'moowp'); ?>" name="add-moosocial-links-menu-item" id="submit-moosocial-links" />
        <span class="spinner" style="position: absolute; left: 50%; top: 1px; margin-left: -10px;"></span>
      </span>
      <span class="add-to-menu" style="margin-right: 3px; margin-left: 3px;">
        <a class="button-secondary right" href="?moosocialmenu=load"><?php esc_attr_e('Reload Menu', 'moowp'); ?></a>
      </span>
    </p>
    <?php endif; ?>
</div>