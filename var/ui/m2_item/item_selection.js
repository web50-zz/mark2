ui.m2_item.item_selection = Ext.extend(ui.m2_item.grid, {
	menuSelect: "Выбрать",
	cnfrmTitle: "Подтверждение",
	fldTitle: "Название",
	fldId:"ID",
	fldArticul: "Артикул",

	Selection: function(){
		var row = this.getSelectionModel().getSelected();
		var id = row.get('id');
		var title = row.get('title');
		this.fireEvent('selected', {
			item_id: id,
			item_title: title
		});
	},




	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		srchType = new Ext.form.ComboBox({
			width: 100,
			store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
				['title', this.fldTitle],
				['id', this.fldId],
				['article', this.fldArticul]
			]}), value: 'title',
			valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
		});
		srchField = new Ext.form.TextField({text:'Название'});
		srchSubmit = function(){
			this.setParams({field: srchType.getValue(), query: srchField.getValue()}, true);
		}.createDelegate(this);
		srchField.on('specialkey', function(field, e){if (e.getKey() == e.ENTER) srchSubmit()});
		var srchBttOk = new Ext.Toolbar.Button({
			text: this.bttFind,
			iconCls:'find',
			handler: srchSubmit
		})
		srchBttCancel = new Ext.Toolbar.Button({
			text: this.bttReset,
			iconCls:'cancel',
			handler: function search_submit(){
				this.setParams({field: '', query: ''}, true);
			},
			scope: this
		})

		Ext.apply(this, {
			tbar: [
				srchType,srchField, srchBttOk, srchBttCancel,
				'->', {iconCls: 'help', handler: function(){showHelp('m2_item')}}
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item.item_selection.superclass.constructor.call(this, config);
		this.on({
			rowcontextmenu: function(grid, rowIndex, e){
				grid.getSelectionModel().selectRow(rowIndex);
				var cmenu = new Ext.menu.Menu({items: [
					{iconCls: 'accept', text: this.menuSelect, handler: this.Selection, scope: this}
				]});
				e.stopEvent();  
				cmenu.showAt(e.getXY());
			},
			dblrowclick: this.Selection,
			scope: this
		});
	},

	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config){
		config = config || {};
		Ext.apply(this, config, config);
	},

	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o){
	}
});
