ui.m2_chars_types.types_selector = Ext.extend(ui.m2_chars_types.tree, {
	bttPAdd: "Добавить подраздел",
	bttVAdd: "Добавить значение",
	bttLAdd: "Создать ссылку на проект",
	bttGAdd: "Добавить",
	bttPEdit: "Изменить",
	bttGEdit: "Изменить",
	bttDelete: "Удалить",
	bttSelect: "Выбрать",
	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эту страницу?",

	addTitle: "Добавление характеристики",
	editTitle: "Изменение характеристики",

	AddProject: function(pid){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_chars_types.project_form();
				var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', maximizable: true, width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(isNew, respData, formData){this.fireEvent('node_saved', isNew, respData, formData)},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({id: 0, pid: pid})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_chars_types', 'project_form');
	},
	AddLink: function(node){
		var pNode = node.parentNode;
		var pid = pNode.id;
		Ext.Ajax.request({
			url: 'di/m2_chars_types/link.do',
			params: {pid: pid, lid: node.id},
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (d.success)
					this.fireEvent('node_linked', d.data.id, pid, node);
				else
					showError('Во время линкования возникли ошибки.');
			},
			scope: this
		});
	},
	EditProject: function(id){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_chars_types.project_form();
				var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', maximizable: true, width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(isNew, respData, formData){this.fireEvent('node_saved', isNew, respData, formData)},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({id: id, pid: 0})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_chars_types', 'project_form');
	},
	AddValue: function(pid){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_chars_types.value_form();
				var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(isNew, respData, formData){this.fireEvent('node_saved', isNew, respData, formData)},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({id: 0, pid: pid})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_chars_types', 'value_form');
	},
	EditValue: function(id){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_chars_types.value_form();
				var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(isNew, respData, formData){this.fireEvent('node_saved', isNew, respData, formData)},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({id: id, pid: 0})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_chars_types', 'value_form');
	},

	Move: function(tree, node, oldParent, newParent, index){
		Ext.Ajax.request({
			url: 'di/m2_chars_types/move.do',
			params: {_sid: node.id, pid: newParent.id, ind: index},
			disableCaching: true,
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (d.success == false) showError(d.errors);
			},
			failure: function(result, request){
				showError('Внутренняя ошибка сервера');
			},
			scope: this
		});
	},

	Delete: function(id, title){
		Ext.Msg.confirm(this.cnfrmTitle, 'Вы действительно хотите удалить альбом "'+(title || id)+'"?', function(btn){
			if (btn == "yes") Ext.Ajax.request({
					url: 'di/m2_chars_types/unset.do',
					params: {_sid: id},
					callback: function(options, success, response){
						var d = Ext.util.JSON.decode(response.responseText);
						if (d.success)
							this.fireEvent('node_deleted', id);
						else
							showError('Во время удаления возникли ошибки.');
					},
					scope: this
				});
		}, this);
	},
	SelectChar: function(node){
		if(node.attributes.type == 2){
			showError('Вы пытаетесь выбрать один из вариантов вышестоящей характеристики');
			return;
		}
		this.fireEvent('selected2', {id:node.id, title: node.text,type: node.attributes.type,char_type: node.attributes.char_type});
	},
	constructor: function(config){
		config = config || {};
		Ext.apply(this, {
			enableDD: true,
			tbar: [
//				{id: 'add', iconCls: 'add', text: this.bttGAdd, handler: this.AddProject.createDelegate(this, [1])},
				'->', {iconCls: 'help', handler: function(){showHelp('m2_chars_types')}}
			]
		});
		Ext.apply(this, config);
		ui.m2_chars_types.types_selector.superclass.constructor.call(this, config);
		this.on({
			node_saved: function(isNew, respData, formData){
				if (isNew){
					var node = new Ext.tree.AsyncTreeNode({id: respData.id, text: formData.title, type: formData.type, expanded: true});
					node.attributes.type = formData.type;
					node.attributes.char_type = formData.char_type;
					node.attributes.name = formData.name;
					if(formData.type == 2){
						node.attributes.iconCls = 'page_white_database';
					}
					this.getNodeById(formData.pid).appendChild(node);
				}else{
					var node = this.getNodeById(respData.id);
					node.setText(formData.title);
					node.attributes.type = formData.type;
					node.attributes.char_type = formData.char_type;
					node.attributes.name = formData.name;
					var fn = function(node){
						if (node.attributes.type == 2 && node.attributes.link_id == respData.id) 
						{
							node.setText(formData.title);
						}
						if (node.hasChildNodes()) node.eachChild(fn, this);
					}
					this.getRootNode().eachChild(fn, this);
				}
			},
			node_linked: function(id, pid, node){
				var node = new Ext.tree.AsyncTreeNode({id: id, text: node.text, iconCls: 'page_white_database', type: 2, link_id: node.id, expanded: true});
				this.getNodeById(pid).appendChild(node);
			},
			node_deleted: function(id){
				var node = this.getNodeById(id);
				node.remove();
				this.fireEvent('removenode', id);
			},
			contextmenu: function(node, e){
				var id = node.id;
				var type = node.attributes.type;
				var char_type = node.attributes.char_type;
				var items = new Array();
				if(char_type == 1){
//					items.push({iconCls: 'pencil', text: this.bttPEdit, handler: this.EditProject.createDelegate(this, [id])});
//					items.push({iconCls: 'add', text: this.bttPAdd, handler: this.AddProject.createDelegate(this, [id])});
					items.push({iconCls: 'add', text: this.bttSelect, handler: this.SelectChar.createDelegate(this, [node])});
				}
				if(char_type == 0 && type != 2){
//					items.push({iconCls: 'pencil', text: this.bttPEdit, handler: this.EditProject.createDelegate(this, [id])});
//					items.push({iconCls: 'add', text: this.bttPAdd, handler: this.AddProject.createDelegate(this, [id])});
					items.push({iconCls: 'add', text: this.bttSelect, handler: this.SelectChar.createDelegate(this, [node])});
				}
				if(type ==2){
//					items.push({iconCls: 'pencil', text: this.bttPEdit, handler: this.EditValue.createDelegate(this, [id])});
				}
//				items.push({iconCls: 'delete', text: this.bttDelete, handler: this.Delete.createDelegate(this, [id, node.text])});

				var cmenu = new Ext.menu.Menu({items: items});
				e.stopEvent();
				cmenu.showAt(e.getXY());
			},
			movenode: this.Move,
			click: function(node, e){
				this.fireEvent('node_changed', node.id, node);
			},
			dblclick: function(node,e){
				this.SelectChar(node);
			},
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
