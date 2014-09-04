var Styles = {
	Class : function(){

		styles = {
		  'Point': [new ol.style.Style({
		    image: new ol.style.Circle({
			  radius: 5,
			  fill: new ol.style.Fill({
			      color: 'rgba(255, 0, 0, 0.4)'
			    }),
			  stroke: new ol.style.Stroke({color: 'red', width: 1})
			})
		  })],
		  'LineString': [new ol.style.Style({
		    stroke: new ol.style.Stroke({
		      color: 'green',
		      width: 1
		    })
		  })],
		  'MultiLineString': [new ol.style.Style({
		    stroke: new ol.style.Stroke({
		      color: 'green',
		      width: 1
		    })
		  })],
		  'MultiPoint': [new ol.style.Style({
		    image: new ol.style.Circle({
			  radius: 5,
			  fill: new ol.style.Fill({
			      color: 'rgba(255, 0, 0, 0.4)'
			    }),
			  stroke: new ol.style.Stroke({color: 'red', width: 1})
			})
		  })],
		  'MultiPolygon': [new ol.style.Style({
		    stroke: new ol.style.Stroke({
		      color: 'yellow',
		      width: 1
		    }),
		    fill: new ol.style.Fill({
		      color: 'rgba(255, 255, 0, 0.1)'
		    })
		  })],
		  'Polygon': [new ol.style.Style({
		    stroke: new ol.style.Stroke({
		      color: 'blue',
		      lineDash: [4],
		      width: 3
		    }),
		    fill: new ol.style.Fill({
		      color: 'rgba(0, 0, 255, 0.1)'
		    })
		  })],
		  'GeometryCollection': [new ol.style.Style({
		    stroke: new ol.style.Stroke({
		      color: 'magenta',
		      width: 2
		    }),
		    fill: new ol.style.Fill({
		      color: 'magenta'
		    }),
		    image: new ol.style.Circle({
		      radius: 10,
		      fill: null,
		      stroke: new ol.style.Stroke({
		        color: 'magenta'
		      })
		    })
		  })],
		  'Circle': [new ol.style.Style({
		    stroke: new ol.style.Stroke({
		      color: 'red',
		      width: 2
		    }),
		    fill: new ol.style.Fill({
		      color: 'rgba(255,0,0,0.2)'
		    })
		  })]
		};

		this.styleFunction = function(feature, resolution) {
			return this.styles[feature.getGeometry().getType()]
		};
	}
};