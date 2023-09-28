<div id="moosocial-links" class="posttypediv">
    <div id="tabs-panel-login-links-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
        <ul id="moosocial-linkschecklist" class="list:moosocial-links categorychecklist form-no-clear">
            <?php if(empty($elems_obj)): ?>
                <?php echo _('No items.', 'moosocial'); ?>
            <?php else: ?>
                <?php echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $elems_obj), 0, (object) array('walker' => $walker)); ?>
            <?php endif; ?>
        </ul>
    </div>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit"<?php disabled($nav_menu_selected_id, 0); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu', 'moosocial'); ?>" name="add-moosocial-links-menu-item" id="submit-moosocial-links" />
        <span class="spinner"></span>
      </span>
    </p>
</div>