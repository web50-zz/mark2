ui.m2_item.choice = Ext.extend(ui.m2_item.grid2, {
	pagerSize: 50,
	applyParams: function(params){
		params = params || {};
		Ext.apply(this.getStore().baseParams, params);
		this.getStore().load({params:{start: 0, limit: this.pagerSize}});
	},
	aChoose: function(){
		this.fireEvent('choosen_all', this.getStore());
	},
	mChoose: function(){
		var s = this.getSelectionModel().getSelections();
		var id = new Array();
		var cid = new Array();
		for (var i = 0; i < s.length; i++)
		{
			id.push(s[i].get('id'));
			cid.push(s[i].get('company_id'));
		}
		this.fireEvent('choosen', this, id, cid, s);
	},
	Choose: function(){
		var r = this.getSelectionModel().getSelected();
		this.fireEvent('choosen', this, r.get('id'), r.get('company_id'), r);
	},
	/**
	 * @constructor
	 */
	constructor: function(config){
		Ext.apply(this, {
			bttSearch: 'Найти',
			bttCancel: 'Сбросить'
		});
		var srchType = new Ext.form.ComboBox({fieldLabel: 'Поиск', width: 150,
			store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
				['title', 'title'],
			]}), value: 'title', valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
		});
		var srchField = new Ext.form.TextField({
			width: 200,
			listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER){
						var s = this.getStore();
						Ext.apply(s.baseParams, {query: field.getValue(), field: srchType.getValue()});
						s.reload();
					}
				},
				scope: this
			}
		});
		var srchBttOk = new Ext.Toolbar.Button({
			text: this.bttSearch,
			iconCls: 'find',
			handler: function search_submit(){
				var s = this.getStore();
				Ext.apply(s.baseParams, {query: srchField.getValue(), field: srchType.getValue()});
				s.reload();
			},
			scope: this
		});
		var srchBttCancel = new Ext.Toolbar.Button({
			text: this.bttCancel,
			iconCls: 'cancel',
			handler: function search_submit(){
				var s = this.getStore();
				srchField.setValue('');
				Ext.apply(s.baseParams, {query: null, field: null});
				s.reload();
			},
			scope: this
		});
		Ext.apply(this, {
			tbar: [
				{iconCls: 'group_add', text: 'Добавить всех', handler: this.aChoose, scope: this},
				{iconCls: 'add', text: 'Добавить выбранных', handler: this.mChoose, scope: this},
				'-',
				srchType, srchField, srchBttOk, srchBttCancel,
				'->', {iconCls: 'help', handler: function(){showHelp('y_comp_contanct_choice')}}
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item.choice.superclass.constructor.call(this, config);
		this.on({
			rowcontextmenu: function(grid, rowIndex, e){
				grid.getSelectionModel().selectRow(rowIndex);
				var cmenu = new Ext.menu.Menu({items: [
					{iconCls: 'user_go', text: "Выбрать", handler: this.Choose, scope: this}
				]});
				e.stopEvent();  
				cmenu.showAt(e.getXY());
			},
			rowdblclick: this.Choose,
			scope: this
		})
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
