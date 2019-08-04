var ginge = (function() {
	var settings = {
		mapType: 'google',
		branchCount: 0,
		famLegend: null
	};
	var template = {};
	var parseNames = {};
	var onReady = function(options) {
		loadTemplates();
		settings = jQuery.extend(settings, options || (options = {}));
	};
	var getCookie = function(sName) {
		sName = sName.toLowerCase();
		var oCrumbles = document.cookie.split(';');
		for(var i = 0; i < oCrumbles.length; i++) {
			var oPair = oCrumbles[i].split('=');
			var sKey = decodeURIComponent(oPair[0].trim().toLowerCase());
			var sValue = oPair.length > 1 ? oPair[1] : '';
			if(sKey == sName) return decodeURIComponent(sValue);
		}
		return '';
	}
	var setCookie = function(sName, sValue) {
		var oDate = new Date();
		oDate.setYear(oDate.getFullYear() + 1);
		var sCookie = encodeURIComponent(sName) + '=' + encodeURIComponent(sValue) + ';expires=' + oDate.toGMTString() + ';path=/';
		document.cookie = sCookie;
	}
	var clearCookie = function(sName) {
		setCookie(sName, '');
	}
	var loadIndi = function(id, hilight, treetop) {
		jQuery.ajax({
			url: "/wp-json/genealogical-tree/v1/member/indi_" + id + ".js",
			cache: true,
			dataType: "json"
		}).done(function(jsonIndi) {
			var childof = jsonIndi.root.indi.childof;
			console.log(jsonIndi.root.indi);
			if(childof) {
				jQuery.ajax({
					url: "/wp-json/genealogical-tree/v1/family/fam_" + childof + ".js",
					cache: true,
					dataType: "json"
				}).done(function(jsonFam) {
					treeTop(jsonIndi.root.indi, jsonFam.root.fam, hilight);
				}).fail(function(jqXHR, textStatus) {
					alert("id=" + id + " : " + textStatus);
				});
			} else {
				treeTop(jsonIndi.root.indi, null, hilight);
			}
		}).fail(function(jqXHR, textStatus) {
			alert("id=" + id + " : " + textStatus);
		});
	};
	var loadFam = function(famId, indiId, hilight) {
		var url = "/wp-json/genealogical-tree/v1/family/fam_" + famId + ".js";
		jQuery.ajax({
			url: url,
			cache: true,
			dataType: "json"
		}).done(function(json) {
			populateFam(json, indiId, hilight);
		}).fail(function(jqXHR, textStatus) {
			alert(url + " : " + textStatus);
		});
	};
	var addToFamLegend = function(id, first, last, year) {
		if(first == "...") f = "Ω" + first;
		else f = " " + first;
		if(last == "...") l = "Ω" + last;
		else l = " " + last;
		parseNames['names'].push({
			"id": id,
			"first": f,
			"last": l,
			"year": year
		});
	};
	var populateFam = function(json, indiId, hilight) {
		var fam = json.root.fam;
		var viewHelpers = {
			indiId: indiId,
			fb: getCookie('facebook'),
			tw: getCookie('twitter'),
			treeBranch: function(famId, indiId, hilight) {
				settings.branchCount++;
				loadFam(famId, indiId, hilight);
			}
		};
		fam.hilight = hilight;
		var data = {
			fam
		};
		_.extend(data, viewHelpers);
		jQuery("#fam_" + fam.id).html(template.treebranch(data));
		var partner;
		if(indiId == fam.wife.ref) partner = fam.husb;
		else partner = fam.wife;
		var birt = _.findWhere(partner.event, {
			'type': 'birt'
		});
		var birtTxt = "";
		if(birt && birt.date) birtTxt = birt.date.value;
		addToFamLegend(partner.ref, partner.name.first, partner.name.last, birtTxt);
		if(fam.children) {
			_.each(fam.children, function(child) {
				var birt = _.findWhere(child.event, {
					'type': 'birt'
				});
				var birtTxt = "";
				if(birt && birt.date) birtTxt = birt.date.value;
				addToFamLegend(child.ref, child.name.first, child.name.last, birtTxt);
			});
		}
		document.body.scrollLeft = (jQuery(document).width() / 2) - (jQuery("body").prop("clientWidth") / 2);
		settings.branchCount--;
		if(settings.branchCount === 0) {
			refreshLegend();
		}
	};
	var treeTop = function(indi, fam, hilight) {
		indi.hilight = hilight;
		parseNames = JSON.parse('{ "names": [] }');
		settings.branchCount = 1;
		//settings.famLegend.clear();
		var birt = _.findWhere(indi.event, {
			'type': 'birt'
		});
		var birtTxt = "";
		console.log(birt);
		if(birt && birt.date) birtTxt = birt.date.value.substring(0, 4);
		jQuery('#fam-canvas h2').html(indi.name);
		addToFamLegend(indi.id, indi.name.first, indi.name.last, birtTxt);
		if(fam && fam.husb) {
			var birt = _.findWhere(fam.husb.event, {
				'type': 'birt'
			});
			var birtTxt = "";
			if(birt && birt.date) birtTxt = birt.date.value.substring(0, 4);
			addToFamLegend(fam.husb.ref, fam.husb.name.first, fam.husb.name.last, birtTxt);
		}
		if(fam && fam.wife) {
			var birt = _.findWhere(fam.wife.event, {
				'type': 'birt'
			});
			var birtTxt = "";
			if(birt && birt.date) birtTxt = birt.date.value.substring(0, 4);
			addToFamLegend(fam.wife.ref, fam.wife.name.first, fam.wife.name.last, birtTxt);
		}
		var viewHelpers = {
			fb: getCookie('facebook'),
			tw: getCookie('twitter'),
			treeBranch: function(famId, indiId, hilight) {
				loadFam(famId, indiId, hilight);
			},
			treeNoBranch: function() {
				refreshLegend();
			}
		};
		var data = {
			indi,
			fam
		};
		_.extend(data, viewHelpers);
		jQuery('#famTree').html(template.treetop(data));
	};
	var refreshLegend = function() {
		parseNames.names = _.chain(parseNames.names).sortBy(function(name) {
			return name.year;
		}).sortBy(function(name) {
			return name.last;
		}).sortBy(function(name) {
			return name.first;
		}).value();
		_.each(parseNames.names, function(name) {
			name.first = name.first.substr(1);
			name.last = name.last.substr(1);
		});
		jQuery('#cg-sidebar-inner').html(template.treenames(parseNames));
	};
	var loadTemplates = function() {
		var templateNames = ['plachead', 'placbody', 'event', 'parent', 'sibling', 'family', 'reference', 'treenames', 'treetop', 'treetoplegend', 'treebranch', 'treebranchlegend'];
		templateNames.forEach(function(templateName) {
			jQuery.get('/wp-content/plugins/genealogical-tree/public/js/templates/' + templateName + '.js', function(data) {
				template[templateName] = _.template(data);
			}, 'html');
		});
	};
	return {
		onReady: onReady,
		loadIndi: loadIndi
	};
})();

jQuery(document).ready(ginge.onReady());
