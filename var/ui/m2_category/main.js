ui.m2_category.main = Ext.extend(ui.m2_category.tree, {
	bttPAdd: "Добавить подраздел",
//	bttLAdd: "Создать ссылку",
	bttLAdd2: "Добавить ссылку на другой раздел",
	bttGAdd: "Добавить категорию",
	bttIman: "Проиндексировать производителей по категориям",
	bttIchar: "Проиндексировать характеристики по категориям",
	bttIprice: "Проиндексировать цены по категориям",
	bttPEdit: "Изменить",
	bttGEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эту страницу?",

	addTitle: "Добавление",
	editTitle: "Изменение",

	AddNode: function(pid){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_category.node_form();
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
		app.Load('m2_category', 'node_form');
	},
	AddLink: function(node){
		var pNode = node.parentNode;
		var pid = pNode.id;
		console.log(node);
		Ext.Ajax.request({
			url: 'di/m2_category/link.do',
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
	AddLink2: function(node){
		var app = new App();
		var pid = node.id;
		app.on({
			apploaded: function(){
				var f = new ui.m2_category.category_selection();
				var w = new Ext.Window({title: "Выбор категории", maximizable: true, modal: true, layout: 'fit', width: 640, height: 480, items: f});
				f.on({
					selected: function(data){
					Ext.Ajax.request({
						url: 'di/m2_category/link.do',
						params: {pid: pid, lid: data.category_id},
						callback: function(options, success, response){
							var d = Ext.util.JSON.decode(response.responseText);
							if (d.success){
								var node = new Ext.tree.AsyncTreeNode({id: d.data.id, text: data.category_title, iconCls: 'link', type: 2, link_id: data.category_id, expanded: true});
								this.getNodeById(pid).appendChild(node);
							}else{
								showError('Во время линкования возникли ошибки.');
								}
						},
						scope: this
					});
					w.close();
					},
					scope: this
				});
				w.show();
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_category', 'category_selection');
	},
	EditNode: function(id){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_category.node_form();
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
		app.Load('m2_category', 'node_form');
	},

	AddGroup: function(pid){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_category.group_form();
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
		app.Load('m2_category', 'group_form');
	},

	EditGroup: function(id){
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_category.group_form();
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
		app.Load('m2_category', 'group_form');
	},

	Move: function(tree, node, oldParent, newParent, index){
		Ext.Ajax.request({
			url: 'di/m2_category/move.do',
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
		Ext.Msg.confirm(this.cnfrmTitle, 'Вы действительно хотите удалить "'+(title || id)+'"?', function(btn){
			if (btn == "yes") Ext.Ajax.request({
					url: 'di/m2_category/unset.do',
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
	Index_manufacturers: function(id, title){
		Ext.Msg.confirm(this.cnfrmTitle, 'Вы хотите проиндексировать производителей по категориям ?', function(btn){
			if (btn == "yes") Ext.Ajax.request({
					url: 'di/m2_category/index_manufacturers.do',
					params: {_sid: id},
					callback: function(options, success, response){
						var d = Ext.util.JSON.decode(response.responseText);
						if (d.success){
							Ext.Msg.alert('',d.msg);
							this.fireEvent('manufacturers_reindexed', id);
						}else{
							showError('Во время индексации возникли ошибки.');
						}
					},
					scope: this
				});
		}, this);
	},
	Index_chars: function(id, title){
		Ext.Msg.confirm(this.cnfrmTitle, 'Проиндексировать характеристики по категориям ?', function(btn){
			if (btn == "yes") Ext.Ajax.request({
					url: 'di/m2_category/index_chars.do',
					params: {_sid: id},
					callback: function(options, success, response){
						var d = Ext.util.JSON.decode(response.responseText);
						if (d.success){
							Ext.Msg.alert('',d.msg);
							this.fireEvent('chars_reindexed', id);
						}else{
							showError('Во время индексации возникли ошибки.');
						}
					},
					scope: this
				});
		}, this);
	},
	Index_price: function(id, title){
		Ext.Msg.confirm(this.cnfrmTitle, 'Проиндексировать цены по категориям ?', function(btn){
			if (btn == "yes") Ext.Ajax.request({
					url: 'di/m2_category/index_prices.do',
					params: {_sid: id},
					callback: function(options, success, response){
						var d = Ext.util.JSON.decode(response.responseText);
						if (d.success){
							Ext.Msg.alert('',d.msg);
							this.fireEvent('chars_reindexed', id);
						}else{
							showError('Во время индексации возникли ошибки.');
						}
					},
					scope: this
				});
		}, this);
	},

	constructor: function(config){
		config = config || {};
		Ext.apply(this, {
			enableDD: true,
			tbar: [
				{id: 'add', iconCls: 'add', text: this.bttGAdd, handler: this.AddNode.createDelegate(this, [1])},
				{id: 'iman', iconCls: 'pencil', text: this.bttIman, handler: this.Index_manufacturers.createDelegate(this, [1])},
				{id: 'ichar', iconCls: 'pencil', text: this.bttIchar, handler: this.Index_chars.createDelegate(this, [1])},
				{id: 'iprice', iconCls: 'pencil', text: this.bttIprice, handler: this.Index_price.createDelegate(this, [1])},
				'->', {iconCls: 'help', handler: function(){showHelp('m2_category')}}
			]
		});
		Ext.apply(this, config);
		ui.m2_category.main.superclass.constructor.call(this, config);
		this.on({
			node_saved: function(isNew, respData, formData){
				if (isNew){
					var node = new Ext.tree.AsyncTreeNode({id: respData.id, text: formData.title, type: formData.type, expanded: true});
					//node.attributes.type = formData.type;
					this.getNodeById(formData.pid).appendChild(node);
				}else{
					var node = this.getNodeById(respData.id);
					node.setText(formData.title);
					var fn = function(node){
						if (node.attributes.type == 2 && node.attributes.link_id == respData.id) node.setText(formData.title);
						if (node.hasChildNodes()) node.eachChild(fn, this);
					}
					this.getRootNode().eachChild(fn, this);
				}
			},
			node_linked: function(id, pid, node){
				var node = new Ext.tree.AsyncTreeNode({id: id, text: node.text, iconCls: 'link', type: 2, link_id: node.id, expanded: true});
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

				var items = new Array();
				if (type == 0){
					items.push({iconCls: 'add', text: this.bttPAdd, handler: this.AddNode.createDelegate(this, [id])});
					items.push({iconCls: 'pencil', text: this.bttGEdit, handler: this.EditGroup.createDelegate(this, [id])});
				}else if (type == 1){
					items.push({iconCls: 'pencil', text: this.bttPEdit, handler: this.EditNode.createDelegate(this, [id])});
					items.push({iconCls: 'add', text: this.bttPAdd, handler: this.AddNode.createDelegate(this, [id])});
				//	items.push({iconCls: 'link_add', text: this.bttLAdd, handler: this.AddLink.createDelegate(this, [node])}); // old style
					items.push({iconCls: 'link_add', text: this.bttLAdd2, handler: this.AddLink2.createDelegate(this, [node])});
				}else if (type == 2){
					items.push({iconCls: 'pencil', text: this.bttPEdit, handler: this.EditNode.createDelegate(this, [node.attributes.link_id])});
				}
				items.push({iconCls: 'delete', text: this.bttDelete, handler: this.Delete.createDelegate(this, [id, node.text])});

				var cmenu = new Ext.menu.Menu({items: items});
				e.stopEvent();
				cmenu.showAt(e.getXY());
			},
			movenode: this.Move,
			click: function(node, e){
				this.fireEvent('node_changed', node.id, node);
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
