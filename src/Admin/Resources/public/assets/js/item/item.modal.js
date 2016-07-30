
modal.on('modal-item-edit',function(container,data){
	var el = container.find('[item-data-form-edit]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	item.request.getForEdit(table,id);
});


modal.on('modal-item-get',function(container,data){
	var el = container.find('[item-data-form-get]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	item.request.get(table,id);
});


/**
 * Data in modal delete
 */
modal.on('modal-item-delete',function(el,data){
	var del = el.find('[data-item-remove]');
	del.attr('data-item-table',data['data-modal-item-table']);
	del.attr('data-item-id',data['data-modal-item-id']);
});

/**
 * Data in modal delete multiple
 */
modal.on('modal-item-delete-multiple',function(el,data){
	var del = el.find('[data-item-multiple-delete]');
	var table = item.getTable(data['data-modal-item-table']);
	var ids = item.getSelectedIds(table);
	el.find('.data-modal-item-ids').html(ids.join(","));
	del.attr('data-item-table',data['data-modal-item-table']);
});

/**
 * Data in modal add
 */
modal.on('modal-item-add',function(el,data){
	var el = el.find('[item-data-form-add]');
	el.attr('data-item-table',data['data-modal-item-table']);
});
