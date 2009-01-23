/* Javascript plotting library for jQuery, v. 0.5.
 *
 * Released under the MIT license by IOLA, December 2007.
 *
 */
(function($){function Plot(u,z,A){var B=[],options={colors:["#edc240","#afd8f8","#cb4b4b","#4da74d","#9440ed"],legend:{show:true,noColumns:1,labelFormatter:null,labelBoxBorderColor:"#ccc",container:null,position:"ne",margin:5,backgroundColor:null,backgroundOpacity:0.85},xaxis:{mode:null,min:null,max:null,autoscaleMargin:null,ticks:null,tickFormatter:null,labelWidth:null,labelHeight:null,tickDecimals:null,tickSize:null,minTickSize:null,monthNames:null,timeformat:null},yaxis:{autoscaleMargin:0.02},x2axis:{autoscaleMargin:null},y2axis:{autoscaleMargin:0.02},points:{show:false,radius:3,lineWidth:2,fill:true,fillColor:"#ffffff"},lines:{lineWidth:2,fill:false,fillColor:null},bars:{show:false,lineWidth:2,barWidth:1,fill:true,fillColor:null,align:"left"},grid:{color:"#545454",backgroundColor:null,tickColor:"#dddddd",labelMargin:5,borderWidth:2,borderColor:null,markings:null,markingsColor:"#f4f4f4",markingsLineWidth:2,clickable:false,hoverable:false,autoHighlight:true,mouseActiveRadius:10},selection:{mode:null,color:"#e8cfac"},crosshair:{mode:null,color:"#aa0000"},shadowSize:4},canvas=null,overlay=null,eventHolder=null,ctx=null,octx=null,target=$(u),axes={xaxis:{},yaxis:{},x2axis:{},y2axis:{}},plotOffset={left:0,right:0,top:0,bottom:0},canvasWidth=0,canvasHeight=0,plotWidth=0,plotHeight=0,workarounds={};this.setData=setData;this.setupGrid=setupGrid;this.draw=draw;this.clearSelection=clearSelection;this.setSelection=setSelection;this.getCanvas=function(){return canvas};this.getPlotOffset=function(){return plotOffset};this.getData=function(){return B};this.getAxes=function(){return axes};this.setCrosshair=setCrosshair;this.clearCrosshair=function(){setCrosshair(null)};this.highlight=highlight;this.unhighlight=unhighlight;parseOptions(A);setData(z);constructCanvas();setupGrid();draw();function setData(d){B=parseData(d);fillInSeriesOptions();processData()}function parseData(d){var a=[];for(var i=0;i<d.length;++i){var s;if(d[i].data){s={};for(var v in d[i])s[v]=d[i][v]}else{s={data:d[i]}}a.push(s)}return a}function parseOptions(o){$.extend(true,options,o);if(options.grid.borderColor==null)options.grid.borderColor=options.grid.color if(options.xaxis.noTicks&&options.xaxis.ticks==null)options.xaxis.ticks=options.xaxis.noTicks;if(options.yaxis.noTicks&&options.yaxis.ticks==null)options.yaxis.ticks=options.yaxis.noTicks;if(options.grid.coloredAreas)options.grid.markings=options.grid.coloredAreas;if(options.grid.coloredAreasColor)options.grid.markingsColor=options.grid.coloredAreasColor}function fillInSeriesOptions(){var i;var a=B.length,usedColors=[],assignedColors=[];for(i=0;i<B.length;++i){var b=B[i].color;if(b!=null){--a;if(typeof b=="number")assignedColors.push(b);else usedColors.push(parseColor(B[i].color))}}for(i=0;i<assignedColors.length;++i){a=Math.max(a,assignedColors[i]+1)}var d=[],variation=0;i=0;while(d.length<a){var c;if(options.colors.length==i)c=new Color(100,100,100);else c=parseColor(options.colors[i]);var e=variation%2==1?-1:1;var f=1+e*Math.ceil(variation/2)*0.2;c.scale(f,f,f);d.push(c);++i;if(i>=options.colors.length){i=0;++variation}}var g=0,s;for(i=0;i<B.length;++i){s=B[i];if(s.color==null){s.color=d[g].toString();++g}else if(typeof s.color=="number")s.color=d[s.color].toString();s.lines=$.extend(true,{},options.lines,s.lines);s.points=$.extend(true,{},options.points,s.points);s.bars=$.extend(true,{},options.bars,s.bars);if(s.lines.show==null&&!s.bars.show&&!s.points.show)s.lines.show=true;if(s.shadowSize==null)s.shadowSize=options.shadowSize;if(!s.xaxis)s.xaxis=axes.xaxis;if(s.xaxis==1)s.xaxis=axes.xaxis;else if(s.xaxis==2)s.xaxis=axes.x2axis;if(!s.yaxis)s.yaxis=axes.yaxis;if(s.yaxis==1)s.yaxis=axes.yaxis;else if(s.yaxis==2)s.yaxis=axes.y2axis}}function processData(){var a=Number.POSITIVE_INFINITY,bottomSentry=Number.NEGATIVE_INFINITY,axis;for(axis in axes){axes[axis].datamin=a;axes[axis].datamax=bottomSentry;axes[axis].min=options[axis].min;axes[axis].max=options[axis].max;axes[axis].used=false}for(var i=0;i<B.length;++i){var b=B[i].data,axisx=B[i].xaxis,axisy=B[i].yaxis,mindelta=0,maxdelta=0;if(B[i].bars.show){mindelta=B[i].bars.align=="left"?0:-B[i].bars.barWidth/2;maxdelta=mindelta+B[i].bars.barWidth}axisx.used=axisy.used=true;for(var j=0;j<b.length;++j){if(b[j]==null)continue;var x=b[j][0],y=b[j][1];if(x!=null&&!isNaN(x=+x)){if(x+mindelta<axisx.datamin)axisx.datamin=x+mindelta;if(x+maxdelta>axisx.datamax)axisx.datamax=x+maxdelta}if(y!=null&&!isNaN(y=+y)){if(y<axisy.datamin)axisy.datamin=y;if(y>axisy.datamax)axisy.datamax=y}if(x==null||y==null||isNaN(x)||isNaN(y))b[j]=null}}for(axis in axes){if(axes[axis].datamin==a)axes[axis].datamin=0;if(axes[axis].datamax==bottomSentry)axes[axis].datamax=1}}function constructCanvas(){canvasWidth=target.width();canvasHeight=target.height();target.html("");if(target.css("position")=='static')target.css("position","relative");if(canvasWidth<=0||canvasHeight<=0)throw"Invalid dimensions for plot, width = "+canvasWidth+", height = "+canvasHeight;canvas=$('<canvas width="'+canvasWidth+'" height="'+canvasHeight+'"></canvas>').appendTo(target).get(0);if($.browser.msie)canvas=window.G_vmlCanvasManager.initElement(canvas);ctx=canvas.getContext("2d");overlay=$('<canvas style="position:absolute;left:0px;top:0px;" width="'+canvasWidth+'" height="'+canvasHeight+'"></canvas>').appendTo(target).get(0);if($.browser.msie)overlay=window.G_vmlCanvasManager.initElement(overlay);octx=overlay.getContext("2d");eventHolder=$([overlay,canvas]);if(options.selection.mode!=null||options.crosshair.mode!=null||options.grid.hoverable){eventHolder.each(function(){this.onmousemove=onMouseMove});if(options.selection.mode!=null)eventHolder.mousedown(onMouseDown)}if(options.crosshair.mode!=null)eventHolder.mouseout(onMouseOut);if(options.grid.clickable)eventHolder.click(onClick)}function setupGrid(){function setupAxis(a,b){setRange(a,b);prepareTickGeneration(a,b);setTicks(a,b);if(a==axes.xaxis||a==axes.x2axis){a.p2c=function(p){return(p-a.min)*a.scale};a.c2p=function(c){return a.min+c/a.scale}}else{a.p2c=function(p){return(a.max-p)*a.scale};a.c2p=function(p){return a.max-p/a.scale}}}for(var d in axes)setupAxis(axes[d],options[d]);setSpacing();insertLabels();insertLegend()}function setRange(a,b){var c=b.min!=null?+b.min:a.datamin;var d=b.max!=null?+b.max:a.datamax;if(d-c==0.0){var e=d==0?1:0.01;if(b.min==null)c-=e;if(b.max==null||b.min!=null)d+=e}else{var f=b.autoscaleMargin;if(f!=null){if(b.min==null){c-=(d-c)*f;if(c<0&&a.datamin>=0)c=0}if(b.max==null){d+=(d-c)*f;if(d>0&&a.datamax<=0)d=0}}}a.min=c;a.max=d}function prepareTickGeneration(h,j){var k;if(typeof j.ticks=="number"&&j.ticks>0)k=j.ticks;else if(h==axes.xaxis||h==axes.x2axis)k=canvasWidth/100;else k=canvasHeight/60;var l=(h.max-h.min)/k;var m,generator,unit,formatter,i,magn,norm;if(j.mode=="time"){var n={"second":1000,"minute":60*1000,"hour":60*60*1000,"day":24*60*60*1000,"month":30*24*60*60*1000,"year":365.2425*24*60*60*1000};var o=[[1,"second"],[2,"second"],[5,"second"],[10,"second"],[30,"second"],[1,"minute"],[2,"minute"],[5,"minute"],[10,"minute"],[30,"minute"],[1,"hour"],[2,"hour"],[4,"hour"],[8,"hour"],[12,"hour"],[1,"day"],[2,"day"],[3,"day"],[0.25,"month"],[0.5,"month"],[1,"month"],[2,"month"],[3,"month"],[6,"month"],[1,"year"]];var p=0;if(j.minTickSize!=null){if(typeof j.tickSize=="number")p=j.tickSize;else p=j.minTickSize[0]*n[j.minTickSize[1]]}for(i=0;i<o.length-1;++i)if(l<(o[i][0]*n[o[i][1]]+o[i+1][0]*n[o[i+1][1]])/2&&o[i][0]*n[o[i][1]]>=p)break;m=o[i][0];unit=o[i][1];if(unit=="year"){magn=Math.pow(10,Math.floor(Math.log(l/n.year)/Math.LN10));norm=(l/n.year)/magn;if(norm<1.5)m=1;else if(norm<3)m=2;else if(norm<7.5)m=5;else m=10;m*=magn}if(j.tickSize){m=j.tickSize[0];unit=j.tickSize[1]}generator=function(a){var b=[],tickSize=a.tickSize[0],unit=a.tickSize[1],d=new Date(a.min);var c=tickSize*n[unit];if(unit=="second")d.setUTCSeconds(floorInBase(d.getUTCSeconds(),tickSize));if(unit=="minute")d.setUTCMinutes(floorInBase(d.getUTCMinutes(),tickSize));if(unit=="hour")d.setUTCHours(floorInBase(d.getUTCHours(),tickSize));if(unit=="month")d.setUTCMonth(floorInBase(d.getUTCMonth(),tickSize));if(unit=="year")d.setUTCFullYear(floorInBase(d.getUTCFullYear(),tickSize));d.setUTCMilliseconds(0);if(c>=n.minute)d.setUTCSeconds(0);if(c>=n.hour)d.setUTCMinutes(0);if(c>=n.day)d.setUTCHours(0);if(c>=n.day*4)d.setUTCDate(1);if(c>=n.year)d.setUTCMonth(0);var e=0,v=Number.NaN,prev;do{prev=v;v=d.getTime();b.push({v:v,label:a.tickFormatter(v,a)});if(unit=="month"){if(tickSize<1){d.setUTCDate(1);var f=d.getTime();d.setUTCMonth(d.getUTCMonth()+1);var g=d.getTime();d.setTime(v+e*n.hour+(g-f)*tickSize);e=d.getUTCHours();d.setUTCHours(0)}else d.setUTCMonth(d.getUTCMonth()+tickSize)}else if(unit=="year"){d.setUTCFullYear(d.getUTCFullYear()+tickSize)}else d.setTime(v+c)}while(v<a.max&&v!=prev);return b};formatter=function(v,a){var d=new Date(v);if(j.timeformat!=null)return $.plot.formatDate(d,j.timeformat,j.monthNames);var t=a.tickSize[0]*n[a.tickSize[1]];var b=a.max-a.min;if(t<n.minute)fmt="%h:%M:%S";else if(t<n.day){if(b<2*n.day)fmt="%h:%M";else fmt="%b %d %h:%M"}else if(t<n.month)fmt="%b %d";else if(t<n.year){if(b<n.year)fmt="%b";else fmt="%b %y"}else fmt="%y";return $.plot.formatDate(d,fmt,j.monthNames)}}else{var q=j.tickDecimals;var r=-Math.floor(Math.log(l)/Math.LN10);if(q!=null&&r>q)r=q;magn=Math.pow(10,-r);norm=l/magn;if(norm<1.5)m=1;else if(norm<3){m=2;if(norm>2.25&&(q==null||r+1<=q)){m=2.5;++r}}else if(norm<7.5)m=5;else m=10;m*=magn;if(j.minTickSize!=null&&m<j.minTickSize)m=j.minTickSize;if(j.tickSize!=null)m=j.tickSize;h.tickDecimals=Math.max(0,(q!=null)?q:r);generator=function(a){var b=[];var c=floorInBase(a.min,a.tickSize),i=0,v=Number.NaN,prev;do{prev=v;v=c+i*a.tickSize;b.push({v:v,label:a.tickFormatter(v,a)});++i}while(v<a.max&&v!=prev);return b};formatter=function(v,a){return v.toFixed(a.tickDecimals)}}h.tickSize=unit?[m,unit]:m;h.tickGenerator=generator;if($.isFunction(j.tickFormatter))h.tickFormatter=function(v,a){return""+j.tickFormatter(v,a)};else h.tickFormatter=formatter;if(j.labelWidth!=null)h.labelWidth=j.labelWidth;if(j.labelHeight!=null)h.labelHeight=j.labelHeight}function setTicks(a,b){a.ticks=[];if(!a.used)return;if(b.ticks==null)a.ticks=a.tickGenerator(a);else if(typeof b.ticks=="number"){if(b.ticks>0)a.ticks=a.tickGenerator(a)}else if(b.ticks){var c=b.ticks;if($.isFunction(c))c=c({min:a.min,max:a.max});var i,v;for(i=0;i<c.length;++i){var d=null;var t=c[i];if(typeof t=="object"){v=t[0];if(t.length>1)d=t[1]}else v=t;if(d==null)d=a.tickFormatter(v,a);a.ticks[i]={v:v,label:d}}}if(b.autoscaleMargin!=null&&a.ticks.length>0){if(b.min==null)a.min=Math.min(a.min,a.ticks[0].v);if(b.max==null&&a.ticks.length>1)a.max=Math.min(a.max,a.ticks[a.ticks.length-1].v)}}function setSpacing(){function measureXLabels(a){if(a.labelWidth==null)a.labelWidth=canvasWidth/6;if(a.labelHeight==null){labels=[];for(i=0;i<a.ticks.length;++i){l=a.ticks[i].label;if(l)labels.push('<div class="tickLabel" style="float:left;width:'+a.labelWidth+'px">'+l+'</div>')}a.labelHeight=0;if(labels.length>0){var b=$('<div style="position:absolute;top:-10000px;width:10000px;font-size:smaller">'+labels.join("")+'<div style="clear:left"></div></div>').appendTo(target);a.labelHeight=b.height();b.remove()}}}function measureYLabels(a){if(a.labelWidth==null||a.labelHeight==null){var i,labels=[],l;for(i=0;i<a.ticks.length;++i){l=a.ticks[i].label;if(l)labels.push('<div class="tickLabel">'+l+'</div>')}if(labels.length>0){var b=$('<div style="position:absolute;top:-10000px;font-size:smaller">'+labels.join("")+'</div>').appendTo(target);if(a.labelWidth==null)a.labelWidth=b.width();if(a.labelHeight==null)a.labelHeight=b.find("div").height();b.remove()}if(a.labelWidth==null)a.labelWidth=0;if(a.labelHeight==null)a.labelHeight=0}}measureXLabels(axes.xaxis);measureYLabels(axes.yaxis);measureXLabels(axes.x2axis);measureYLabels(axes.y2axis);var c=options.grid.borderWidth;for(i=0;i<B.length;++i)c=Math.max(c,2*(B[i].points.radius+B[i].points.lineWidth/2));plotOffset.left=plotOffset.right=plotOffset.top=plotOffset.bottom=c;var d=options.grid.labelMargin+options.grid.borderWidth;if(axes.xaxis.labelHeight>0)plotOffset.bottom=Math.max(c,axes.xaxis.labelHeight+d);if(axes.yaxis.labelWidth>0)plotOffset.left=Math.max(c,axes.yaxis.labelWidth+d);if(axes.x2axis.labelHeight>0)plotOffset.top=Math.max(c,axes.x2axis.labelHeight+d);if(axes.y2axis.labelWidth>0)plotOffset.right=Math.max(c,axes.y2axis.labelWidth+d);plotWidth=canvasWidth-plotOffset.left-plotOffset.right;plotHeight=canvasHeight-plotOffset.bottom-plotOffset.top;axes.xaxis.scale=plotWidth/(axes.xaxis.max-axes.xaxis.min);axes.yaxis.scale=plotHeight/(axes.yaxis.max-axes.yaxis.min);axes.x2axis.scale=plotWidth/(axes.x2axis.max-axes.x2axis.min);axes.y2axis.scale=plotHeight/(axes.y2axis.max-axes.y2axis.min)}function draw(){drawGrid();for(var i=0;i<B.length;i++){drawSeries(B[i])}}function extractRange(a,b){var c=b+"axis",secondaryAxis=b+"2axis",axis,from,to,reverse;if(a[c]){axis=axes[c];from=a[c].from;to=a[c].to}else if(a[secondaryAxis]){axis=axes[secondaryAxis];from=a[secondaryAxis].from;to=a[secondaryAxis].to}else{axis=axes[c];from=a[b+"1"];to=a[b+"2"]}if(from!=null&&to!=null&&from>to)return{from:to,to:from,axis:axis};return{from:from,to:to,axis:axis}}function drawGrid(){var i;ctx.save();ctx.clearRect(0,0,canvasWidth,canvasHeight);ctx.translate(plotOffset.left,plotOffset.top);if(options.grid.backgroundColor){ctx.fillStyle=getColorOrGradient(options.grid.backgroundColor);ctx.fillRect(0,0,plotWidth,plotHeight)}var a=options.grid.markings;if(a){if($.isFunction(a))a=a({xmin:axes.xaxis.min,xmax:axes.xaxis.max,ymin:axes.yaxis.min,ymax:axes.yaxis.max,xaxis:axes.xaxis,yaxis:axes.yaxis,x2axis:axes.x2axis,y2axis:axes.y2axis});for(i=0;i<a.length;++i){var m=a[i],xrange=extractRange(m,"x"),yrange=extractRange(m,"y");if(xrange.from==null)xrange.from=xrange.axis.min;if(xrange.to==null)xrange.to=xrange.axis.max;if(yrange.from==null)yrange.from=yrange.axis.min;if(yrange.to==null)yrange.to=yrange.axis.max;if(xrange.to<xrange.axis.min||xrange.from>xrange.axis.max||yrange.to<yrange.axis.min||yrange.from>yrange.axis.max)continue;xrange.from=Math.max(xrange.from,xrange.axis.min);xrange.to=Math.min(xrange.to,xrange.axis.max);yrange.from=Math.max(yrange.from,yrange.axis.min);yrange.to=Math.min(yrange.to,yrange.axis.max);if(xrange.from==xrange.to&&yrange.from==yrange.to)continue;xrange.from=xrange.axis.p2c(xrange.from);xrange.to=xrange.axis.p2c(xrange.to);yrange.from=yrange.axis.p2c(yrange.from);yrange.to=yrange.axis.p2c(yrange.to);if(xrange.from==xrange.to||yrange.from==yrange.to){ctx.strokeStyle=m.color||options.grid.markingsColor;ctx.beginPath();ctx.lineWidth=m.lineWidth||options.grid.markingsLineWidth;ctx.moveTo(xrange.from,yrange.from);ctx.lineTo(xrange.to,yrange.to);ctx.stroke()}else{ctx.fillStyle=m.color||options.grid.markingsColor;ctx.fillRect(xrange.from,yrange.to,xrange.to-xrange.from,yrange.from-yrange.to)}}}ctx.lineWidth=1;ctx.strokeStyle=options.grid.tickColor;ctx.beginPath();var v,axis=axes.xaxis;for(i=0;i<axis.ticks.length;++i){v=axis.ticks[i].v;if(v<=axis.min||v>=axes.xaxis.max)continue;ctx.moveTo(Math.floor(axis.p2c(v))+ctx.lineWidth/2,0);ctx.lineTo(Math.floor(axis.p2c(v))+ctx.lineWidth/2,plotHeight)}axis=axes.yaxis;for(i=0;i<axis.ticks.length;++i){v=axis.ticks[i].v;if(v<=axis.min||v>=axis.max)continue;ctx.moveTo(0,Math.floor(axis.p2c(v))+ctx.lineWidth/2);ctx.lineTo(plotWidth,Math.floor(axis.p2c(v))+ctx.lineWidth/2)}axis=axes.x2axis;for(i=0;i<axis.ticks.length;++i){v=axis.ticks[i].v;if(v<=axis.min||v>=axis.max)continue;ctx.moveTo(Math.floor(axis.p2c(v))+ctx.lineWidth/2,-5);ctx.lineTo(Math.floor(axis.p2c(v))+ctx.lineWidth/2,5)}axis=axes.y2axis;for(i=0;i<axis.ticks.length;++i){v=axis.ticks[i].v;if(v<=axis.min||v>=axis.max)continue;ctx.moveTo(plotWidth-5,Math.floor(axis.p2c(v))+ctx.lineWidth/2);ctx.lineTo(plotWidth+5,Math.floor(axis.p2c(v))+ctx.lineWidth/2)}ctx.stroke();if(options.grid.borderWidth){var b=options.grid.borderWidth;ctx.lineWidth=b;ctx.strokeStyle=options.grid.borderColor;ctx.strokeRect(-b/2,-b/2,plotWidth+b,plotHeight+b)}ctx.restore()}function insertLabels(){target.find(".tickLabels").remove();var d='<div class="tickLabels" style="font-size:smaller;color:'+options.grid.color+'">';function addLabels(a,b){for(var i=0;i<a.ticks.length;++i){var c=a.ticks[i];if(!c.label||c.v<a.min||c.v>a.max)continue;d+=b(c,a)}}var e=options.grid.labelMargin+options.grid.borderWidth;addLabels(axes.xaxis,function(a,b){return'<div style="position:absolute;top:'+(plotOffset.top+plotHeight+e)+'px;left:'+(plotOffset.left+b.p2c(a.v)-b.labelWidth/2)+'px;width:'+b.labelWidth+'px;text-align:center" class="tickLabel">'+a.label+"</div>"});addLabels(axes.yaxis,function(a,b){return'<div style="position:absolute;top:'+(plotOffset.top+b.p2c(a.v)-b.labelHeight/2)+'px;right:'+(plotOffset.right+plotWidth+e)+'px;width:'+b.labelWidth+'px;text-align:right" class="tickLabel">'+a.label+"</div>"});addLabels(axes.x2axis,function(a,b){return'<div style="position:absolute;bottom:'+(plotOffset.bottom+plotHeight+e)+'px;left:'+(plotOffset.left+b.p2c(a.v)-b.labelWidth/2)+'px;width:'+b.labelWidth+'px;text-align:center" class="tickLabel">'+a.label+"</div>"});addLabels(axes.y2axis,function(a,b){return'<div style="position:absolute;top:'+(plotOffset.top+b.p2c(a.v)-b.labelHeight/2)+'px;left:'+(plotOffset.left+plotWidth+e)+'px;width:'+b.labelWidth+'px;text-align:left" class="tickLabel">'+a.label+"</div>"});d+='</div>';target.append(d)}function drawSeries(a){if(a.lines.show)drawSeriesLines(a);if(a.bars.show)drawSeriesBars(a);if(a.points.show)drawSeriesPoints(a)}function drawSeriesLines(k){function plotLine(a,b,c,d){var e,cur=null,drawx=null,drawy=null;ctx.beginPath();for(var i=0;i<a.length;++i){e=cur;cur=a[i];if(e==null||cur==null)continue;var f=e[0],y1=e[1],x2=cur[0],y2=cur[1];if(y1<=y2&&y1<d.min){if(y2<d.min)continue;f=(d.min-y1)/(y2-y1)*(x2-f)+f;y1=d.min}else if(y2<=y1&&y2<d.min){if(y1<d.min)continue;x2=(d.min-y1)/(y2-y1)*(x2-f)+f;y2=d.min}if(y1>=y2&&y1>d.max){if(y2>d.max)continue;f=(d.max-y1)/(y2-y1)*(x2-f)+f;y1=d.max}else if(y2>=y1&&y2>d.max){if(y1>d.max)continue;x2=(d.max-y1)/(y2-y1)*(x2-f)+f;y2=d.max}if(f<=x2&&f<c.min){if(x2<c.min)continue;y1=(c.min-f)/(x2-f)*(y2-y1)+y1;f=c.min}else if(x2<=f&&x2<c.min){if(f<c.min)continue;y2=(c.min-f)/(x2-f)*(y2-y1)+y1;x2=c.min}if(f>=x2&&f>c.max){if(x2>c.max)continue;y1=(c.max-f)/(x2-f)*(y2-y1)+y1;f=c.max}else if(x2>=f&&x2>c.max){if(f>c.max)continue;y2=(c.max-f)/(x2-f)*(y2-y1)+y1;x2=c.max}if(drawx!=c.p2c(f)||drawy!=d.p2c(y1)+b)ctx.moveTo(c.p2c(f),d.p2c(y1)+b);drawx=c.p2c(x2);drawy=d.p2c(y2)+b;ctx.lineTo(drawx,drawy)}ctx.stroke()}function plotLineArea(a,b,c){var d,cur=null;var e=Math.min(Math.max(0,c.min),c.max);var f,lastX=0;var g=false;for(var i=0;i<a.length;++i){d=cur;cur=a[i];if(g&&d!=null&&cur==null){ctx.lineTo(b.p2c(lastX),c.p2c(e));ctx.fill();g=false;continue}if(d==null||cur==null)continue;var h=d[0],y1=d[1],x2=cur[0],y2=cur[1];if(h<=x2&&h<b.min){if(x2<b.min)continue;y1=(b.min-h)/(x2-h)*(y2-y1)+y1;h=b.min}else if(x2<=h&&x2<b.min){if(h<b.min)continue;y2=(b.min-h)/(x2-h)*(y2-y1)+y1;x2=b.min}if(h>=x2&&h>b.max){if(x2>b.max)continue;y1=(b.max-h)/(x2-h)*(y2-y1)+y1;h=b.max}else if(x2>=h&&x2>b.max){if(h>b.max)continue;y2=(b.max-h)/(x2-h)*(y2-y1)+y1;x2=b.max}if(!g){ctx.beginPath();ctx.moveTo(b.p2c(h),c.p2c(e));g=true}if(y1>=c.max&&y2>=c.max){ctx.lineTo(b.p2c(h),c.p2c(c.max));ctx.lineTo(b.p2c(x2),c.p2c(c.max));lastX=x2;continue}else if(y1<=c.min&&y2<=c.min){ctx.lineTo(b.p2c(h),c.p2c(c.min));ctx.lineTo(b.p2c(x2),c.p2c(c.min));lastX=x2;continue}var j=h,x2old=x2;if(y1<=y2&&y1<c.min&&y2>=c.min){h=(c.min-y1)/(y2-y1)*(x2-h)+h;y1=c.min}else if(y2<=y1&&y2<c.min&&y1>=c.min){x2=(c.min-y1)/(y2-y1)*(x2-h)+h;y2=c.min}if(y1>=y2&&y1>c.max&&y2<=c.max){h=(c.max-y1)/(y2-y1)*(x2-h)+h;y1=c.max}else if(y2>=y1&&y2>c.max&&y1<=c.max){x2=(c.max-y1)/(y2-y1)*(x2-h)+h;y2=c.max}if(h!=j){if(y1<=c.min)f=c.min;else f=c.max;ctx.lineTo(b.p2c(j),c.p2c(f));ctx.lineTo(b.p2c(h),c.p2c(f))}ctx.lineTo(b.p2c(h),c.p2c(y1));ctx.lineTo(b.p2c(x2),c.p2c(y2));if(x2!=x2old){if(y2<=c.min)f=c.min;else f=c.max;ctx.lineTo(b.p2c(x2),c.p2c(f));ctx.lineTo(b.p2c(x2old),c.p2c(f))}lastX=Math.max(x2,x2old)}if(g){ctx.lineTo(b.p2c(lastX),c.p2c(e));ctx.fill()}}ctx.save();ctx.translate(plotOffset.left,plotOffset.top);ctx.lineJoin="round";var l=k.lines.lineWidth,sw=k.shadowSize;if(l>0&&sw>0){var w=sw/2;ctx.lineWidth=w;ctx.strokeStyle="rgba(0,0,0,0.1)";plotLine(k.data,l/2+w+w/2,k.xaxis,k.yaxis);ctx.strokeStyle="rgba(0,0,0,0.2)";plotLine(k.data,l/2+w/2,k.xaxis,k.yaxis)}ctx.lineWidth=l;ctx.strokeStyle=k.color;setFillStyle(k.lines,k.color);if(k.lines.fill)plotLineArea(k.data,k.xaxis,k.yaxis);if(l>0)plotLine(k.data,0,k.xaxis,k.yaxis);ctx.restore()}function drawSeriesPoints(f){function plotPoints(a,b,c,d,e){for(var i=0;i<a.length;++i){if(a[i]==null)continue;var x=a[i][0],y=a[i][1];if(x<d.min||x>d.max||y<e.min||y>e.max)continue;ctx.beginPath();ctx.arc(d.p2c(x),e.p2c(y),b,0,2*Math.PI,true);if(c)ctx.fill();ctx.stroke()}}function plotPointShadows(a,b,c,d,e){for(var i=0;i<a.length;++i){if(a[i]==null)continue;var x=a[i][0],y=a[i][1];if(x<d.min||x>d.max||y<e.min||y>e.max)continue;ctx.beginPath();ctx.arc(d.p2c(x),e.p2c(y)+b,c,0,Math.PI,false);ctx.stroke()}}ctx.save();ctx.translate(plotOffset.left,plotOffset.top);var g=f.lines.lineWidth,sw=f.shadowSize;if(g>0&&sw>0){var w=sw/2;ctx.lineWidth=w;ctx.strokeStyle="rgba(0,0,0,0.1)";plotPointShadows(f.data,w+w/2,f.points.radius,f.xaxis,f.yaxis);ctx.strokeStyle="rgba(0,0,0,0.2)";plotPointShadows(f.data,w/2,f.points.radius,f.xaxis,f.yaxis)}ctx.lineWidth=f.points.lineWidth;ctx.strokeStyle=f.color;setFillStyle(f.points,f.color);plotPoints(f.data,f.points.radius,f.points.fill,f.xaxis,f.yaxis);ctx.restore()}function drawBar(x,y,a,b,d,e,f,g,c){var h=true,drawRight=true,drawTop=true,drawBottom=false,left=x+a,right=x+b,bottom=0,top=y;if(top<bottom){top=0;bottom=y;drawBottom=true;drawTop=false}if(right<f.min||left>f.max||top<g.min||bottom>g.max)return;if(left<f.min){left=f.min;h=false}if(right>f.max){right=f.max;drawRight=false}if(bottom<g.min){bottom=g.min;drawBottom=false}if(top>g.max){top=g.max;drawTop=false}if(e){c.beginPath();c.moveTo(f.p2c(left),g.p2c(bottom)+d);c.lineTo(f.p2c(left),g.p2c(top)+d);c.lineTo(f.p2c(right),g.p2c(top)+d);c.lineTo(f.p2c(right),g.p2c(bottom)+d);c.fill()}if(h||drawRight||drawTop||drawBottom){c.beginPath();left=f.p2c(left);bottom=g.p2c(bottom);right=f.p2c(right);top=g.p2c(top);c.moveTo(left,bottom+d);if(h)c.lineTo(left,top+d);else c.moveTo(left,top+d);if(drawTop)c.lineTo(right,top+d);else c.moveTo(right,top+d);if(drawRight)c.lineTo(right,bottom+d);else c.moveTo(right,bottom+d);if(drawBottom)c.lineTo(left,bottom+d);else c.moveTo(left,bottom+d);c.stroke()}}function drawSeriesBars(h){function plotBars(a,b,c,d,e,f,g){for(var i=0;i<a.length;i++){if(a[i]==null)continue;drawBar(a[i][0],a[i][1],b,c,d,e,f,g,ctx)}}ctx.save();ctx.translate(plotOffset.left,plotOffset.top);ctx.lineJoin="round";ctx.lineWidth=h.bars.lineWidth;ctx.strokeStyle=h.color;setFillStyle(h.bars,h.color);var j=h.bars.align=="left"?0:-h.bars.barWidth/2;plotBars(h.data,j,j+h.bars.barWidth,0,h.bars.fill,h.xaxis,h.yaxis);ctx.restore()}function setFillStyle(a,b){var d=a.fill;if(!d)return;if(a.fillColor)ctx.fillStyle=a.fillColor;else{var c=parseColor(b);c.a=typeof d=="number"?d:0.4;c.normalize();ctx.fillStyle=c.toString()}}function insertLegend(){target.find(".legend").remove();if(!options.legend.show)return;var a=[];var b=false;for(i=0;i<B.length;++i){if(!B[i].label)continue;if(i%options.legend.noColumns==0){if(b)a.push('</tr>');a.push('<tr>');b=true}var d=B[i].label;if(options.legend.labelFormatter!=null)d=options.legend.labelFormatter(d);a.push('<td class="legendColorBox"><div style="border:1px solid '+options.legend.labelBoxBorderColor+';padding:1px"><div style="width:4px;height:0;border:5px solid '+B[i].color+';overflow:hidden"></div></div></td>'+'<td class="legendLabel">'+d+'</td>')}if(b)a.push('</tr>');if(a.length==0)return;var e='<table style="font-size:smaller;color:'+options.grid.color+'">'+a.join("")+'</table>';if(options.legend.container!=null)$(options.legend.container).html(e);else{var f="",p=options.legend.position,m=options.legend.margin;if(m[0]==null)m=[m,m];if(p.charAt(0)=="n")f+='top:'+(m[1]+plotOffset.top)+'px;';else if(p.charAt(0)=="s")f+='bottom:'+(m[1]+plotOffset.bottom)+'px;';if(p.charAt(1)=="e")f+='right:'+(m[0]+plotOffset.right)+'px;';else if(p.charAt(1)=="w")f+='left:'+(m[0]+plotOffset.left)+'px;';var g=$('<div class="legend">'+e.replace('style="','style="position:absolute;'+f+';')+'</div>').appendTo(target);if(options.legend.backgroundOpacity!=0.0){var c=options.legend.backgroundColor;if(c==null){var h;if(options.grid.backgroundColor&&typeof options.grid.backgroundColor=="string")h=options.grid.backgroundColor;else h=extractColor(g);c=parseColor(h).adjust(null,null,null,1).toString()}var j=g.children();$('<div style="position:absolute;width:'+j.width()+'px;height:'+j.height()+'px;'+f+'background-color:'+c+';"> </div>').prependTo(g).css('opacity',options.legend.backgroundOpacity)}}}var C={pageX:null,pageY:null},selection={first:{x:-1,y:-1},second:{x:-1,y:-1},show:false,active:false},crosshair={pos:{x:-1,y:-1}},highlights=[],clickIsMouseUp=false,redrawTimeout=null,hoverTimeout=null;function findNearbyItem(a,b,c){var d=options.grid.mouseActiveRadius,lowestDistance=d*d+1,item=null,foundPoint=false;function result(i,j){return{datapoint:B[i].data[j],dataIndex:j,series:B[i],seriesIndex:i}}for(var i=0;i<B.length;++i){if(!c(B[i]))continue;var e=B[i].data,axisx=B[i].xaxis,axisy=B[i].yaxis,mx=axisx.c2p(a),my=axisy.c2p(b),maxx=d/axisx.scale,maxy=d/axisy.scale,checkbar=B[i].bars.show,checkpoint=!(B[i].bars.show&&!(B[i].lines.show||B[i].points.show)),barLeft=B[i].bars.align=="left"?0:-B[i].bars.barWidth/2,barRight=barLeft+B[i].bars.barWidth;for(var j=0;j<e.length;++j){if(e[j]==null)continue;var x=e[j][0],y=e[j][1];if(checkbar){if(!foundPoint&&mx>=x+barLeft&&mx<=x+barRight&&my>=Math.min(0,y)&&my<=Math.max(0,y))item=result(i,j)}if(checkpoint){if((x-mx>maxx||x-mx<-maxx)||(y-my>maxy||y-my<-maxy))continue;var f=Math.abs(axisx.p2c(x)-a),dy=Math.abs(axisy.p2c(y)-b),dist=f*f+dy*dy;if(dist<lowestDistance){lowestDistance=dist;foundPoint=true;item=result(i,j)}}}}return item}function onMouseMove(a){var e=a||window.event;if(e.pageX==null&&e.clientX!=null){var c=document.documentElement,b=document.body;C.pageX=e.clientX+(c&&c.scrollLeft||b.scrollLeft||0)-(c.clientLeft||0);C.pageY=e.clientY+(c&&c.scrollTop||b.scrollTop||0)-(c.clientTop||0)}else{C.pageX=e.pageX;C.pageY=e.pageY}if(options.grid.hoverable)triggerClickHoverEvent("plothover",C,function(s){return s["hoverable"]!=false});if(options.crosshair.mode!=null){if(!selection.active){setPositionFromEvent(crosshair.pos,C);triggerRedrawOverlay()}else crosshair.pos.x=-1}if(selection.active){target.trigger("plotselecting",[selectionIsSane()?getSelectionForEvent():null]);updateSelection(C)}}function onMouseDown(e){if(e.which!=1)return;document.body.focus();if(document.onselectstart!==undefined&&workarounds.onselectstart==null){workarounds.onselectstart=document.onselectstart;document.onselectstart=function(){return false}}if(document.ondrag!==undefined&&workarounds.ondrag==null){workarounds.ondrag=document.ondrag;document.ondrag=function(){return false}}setSelectionPos(selection.first,e);C.pageX=null;selection.active=true;$(document).one("mouseup",onSelectionMouseUp)}function onMouseOut(a){if(options.crosshair.mode!=null&&crosshair.pos.x!=-1){crosshair.pos.x=-1;triggerRedrawOverlay()}}function onClick(e){if(clickIsMouseUp){clickIsMouseUp=false;return}triggerClickHoverEvent("plotclick",e,function(s){return s["clickable"]!=false})}function triggerClickHoverEvent(a,b,c){var d=eventHolder.offset(),pos={pageX:b.pageX,pageY:b.pageY},canvasX=b.pageX-d.left-plotOffset.left,canvasY=b.pageY-d.top-plotOffset.top;if(axes.xaxis.used)pos.x=axes.xaxis.c2p(canvasX);if(axes.yaxis.used)pos.y=axes.yaxis.c2p(canvasY);if(axes.x2axis.used)pos.x2=axes.x2axis.c2p(canvasX);if(axes.y2axis.used)pos.y2=axes.y2axis.c2p(canvasY);var e=findNearbyItem(canvasX,canvasY,c);if(e){e.pageX=parseInt(e.series.xaxis.p2c(e.datapoint[0])+d.left+plotOffset.left);e.pageY=parseInt(e.series.yaxis.p2c(e.datapoint[1])+d.top+plotOffset.top)}if(options.grid.autoHighlight){for(var i=0;i<highlights.length;++i){var h=highlights[i];if(h.auto==a&&!(e&&h.series==e.series&&h.point==e.datapoint))unhighlight(h.series,h.point)}if(e)highlight(e.series,e.datapoint,a)}target.trigger(a,[pos,e])}function triggerRedrawOverlay(){if(!redrawTimeout)redrawTimeout=setTimeout(redrawOverlay,30)}function redrawOverlay(){redrawTimeout=null;octx.save();octx.clearRect(0,0,canvasWidth,canvasHeight);octx.translate(plotOffset.left,plotOffset.top);var i,hi;for(i=0;i<highlights.length;++i){hi=highlights[i];if(hi.series.bars.show)drawBarHighlight(hi.series,hi.point);else drawPointHighlight(hi.series,hi.point)}if(selection.show&&selectionIsSane()){octx.strokeStyle=parseColor(options.selection.color).scale(null,null,null,0.8).toString();octx.lineWidth=1;ctx.lineJoin="round";octx.fillStyle=parseColor(options.selection.color).scale(null,null,null,0.4).toString();var x=Math.min(selection.first.x,selection.second.x),y=Math.min(selection.first.y,selection.second.y),w=Math.abs(selection.second.x-selection.first.x),h=Math.abs(selection.second.y-selection.first.y);octx.fillRect(x,y,w,h);octx.strokeRect(x,y,w,h)}if(options.crosshair.mode!=null&&crosshair.pos.x!=-1){octx.strokeStyle=parseColor(options.crosshair.color).scale(null,null,null,0.8).toString();octx.lineWidth=1;ctx.lineJoin="round";var a=crosshair.pos;octx.beginPath();if(options.crosshair.mode.indexOf("x")!=-1){octx.moveTo(a.x,0);octx.lineTo(a.x,plotHeight)}if(options.crosshair.mode.indexOf("y")!=-1){octx.moveTo(0,a.y);octx.lineTo(plotWidth,a.y)}octx.stroke()}octx.restore()}function highlight(s,a,b){if(typeof s=="number")s=B[s];if(typeof a=="number")a=s.data[a];var i=indexOfHighlight(s,a);if(i==-1){highlights.push({series:s,point:a,auto:b});triggerRedrawOverlay()}else if(!b)highlights[i].auto=false}function unhighlight(s,a){if(typeof s=="number")s=B[s];if(typeof a=="number")a=s.data[a];var i=indexOfHighlight(s,a);if(i!=-1){highlights.splice(i,1);triggerRedrawOverlay()}}function indexOfHighlight(s,p){for(var i=0;i<highlights.length;++i){var h=highlights[i];if(h.series==s&&h.point[0]==p[0]&&h.point[1]==p[1])return i}return-1}function drawPointHighlight(a,b){var x=b[0],y=b[1],axisx=a.xaxis,axisy=a.yaxis;if(x<axisx.min||x>axisx.max||y<axisy.min||y>axisy.max)return;var c=a.points.radius+a.points.lineWidth/2;octx.lineWidth=c;octx.strokeStyle=parseColor(a.color).scale(1,1,1,0.5).toString();var d=1.5*c;octx.beginPath();octx.arc(axisx.p2c(x),axisy.p2c(y),d,0,2*Math.PI,true);octx.stroke()}function drawBarHighlight(a,b){octx.lineJoin="round";octx.lineWidth=a.bars.lineWidth;octx.strokeStyle=parseColor(a.color).scale(1,1,1,0.5).toString();octx.fillStyle=parseColor(a.color).scale(1,1,1,0.5).toString();var c=a.bars.align=="left"?0:-a.bars.barWidth/2;drawBar(b[0],b[1],c,c+a.bars.barWidth,0,true,a.xaxis,a.yaxis,octx)}function setPositionFromEvent(a,e){var b=eventHolder.offset();a.x=clamp(0,e.pageX-b.left-plotOffset.left,plotWidth);a.y=clamp(0,e.pageY-b.top-plotOffset.top,plotHeight)}function setCrosshair(a){if(a==null)crosshair.pos.x=-1;else{crosshair.pos.x=clamp(0,a.x!=null?axes.xaxis.p2c(a.x):axes.x2axis.p2c(a.x2),plotWidth);crosshair.pos.y=clamp(0,a.y!=null?axes.yaxis.p2c(a.y):axes.y2axis.p2c(a.y2),plotHeight)}triggerRedrawOverlay()}function getSelectionForEvent(){var a=Math.min(selection.first.x,selection.second.x),x2=Math.max(selection.first.x,selection.second.x),y1=Math.max(selection.first.y,selection.second.y),y2=Math.min(selection.first.y,selection.second.y);var r={};if(axes.xaxis.used)r.xaxis={from:axes.xaxis.c2p(a),to:axes.xaxis.c2p(x2)};if(axes.x2axis.used)r.x2axis={from:axes.x2axis.c2p(a),to:axes.x2axis.c2p(x2)};if(axes.yaxis.used)r.yaxis={from:axes.yaxis.c2p(y1),to:axes.yaxis.c2p(y2)};if(axes.y2axis.used)r.y2axis={from:axes.y2axis.c2p(y1),to:axes.y2axis.c2p(y2)};return r}function triggerSelectedEvent(){var r=getSelectionForEvent();target.trigger("plotselected",[r]);if(axes.xaxis.used&&axes.yaxis.used)target.trigger("selected",[{x1:r.xaxis.from,y1:r.yaxis.from,x2:r.xaxis.to,y2:r.yaxis.to}])}function onSelectionMouseUp(e){if(document.onselectstart!==undefined)document.onselectstart=workarounds.onselectstart;if(document.ondrag!==undefined)document.ondrag=workarounds.ondrag;selection.active=false;updateSelection(e);if(selectionIsSane()){triggerSelectedEvent();clickIsMouseUp=true}else{target.trigger("plotunselected",[]);target.trigger("plotselecting",[null])}return false}function setSelectionPos(a,e){setPositionFromEvent(a,e);if(options.selection.mode=="y"){if(a==selection.first)a.x=0;else a.x=plotWidth}if(options.selection.mode=="x"){if(a==selection.first)a.y=0;else a.y=plotHeight}}function updateSelection(a){if(a.pageX==null)return;setSelectionPos(selection.second,a);if(selectionIsSane()){selection.show=true;triggerRedrawOverlay()}else clearSelection(true)}function clearSelection(a){if(selection.show){selection.show=false;triggerRedrawOverlay();if(!a)target.trigger("plotunselected",[])}}function setSelection(a,b){var c;if(options.selection.mode=="y"){selection.first.x=0;selection.second.x=plotWidth}else{c=extractRange(a,"x");selection.first.x=c.axis.p2c(c.from);selection.second.x=c.axis.p2c(c.to)}if(options.selection.mode=="x"){selection.first.y=0;selection.second.y=plotHeight}else{c=extractRange(a,"y");selection.first.y=c.axis.p2c(c.from);selection.second.y=c.axis.p2c(c.to)}selection.show=true;triggerRedrawOverlay();if(!b)triggerSelectedEvent()}function selectionIsSane(){var a=5;return Math.abs(selection.second.x-selection.first.x)>=a&&Math.abs(selection.second.y-selection.first.y)>=a}function getColorOrGradient(a){if(typeof a=="string")return a;else{var b=ctx.createLinearGradient(0,0,0,plotHeight);for(var i=0,l=a.colors.length;i<l;++i)b.addColorStop(i/(l-1),a.colors[i]);return b}}}$.plot=function(a,b,c){var d=new Plot(a,b,c);return d};$.plot.formatDate=function(d,a,b){var e=function(n){n=""+n;return n.length==1?"0"+n:n};var r=[];var f=false;if(b==null)b=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];for(var i=0;i<a.length;++i){var c=a.charAt(i);if(f){switch(c){case'h':c=""+d.getUTCHours();break;case'H':c=e(d.getUTCHours());break;case'M':c=e(d.getUTCMinutes());break;case'S':c=e(d.getUTCSeconds());break;case'd':c=""+d.getUTCDate();break;case'm':c=""+(d.getUTCMonth()+1);break;case'y':c=""+d.getUTCFullYear();break;case'b':c=""+b[d.getUTCMonth()];break}r.push(c);f=false}else{if(c=="%")f=true;else r.push(c)}}return r.join("")};function floorInBase(n,a){return a*Math.floor(n/a)}function clamp(a,b,c){if(b<a)return a;else if(b>c)return c;else return b}function Color(r,g,b,a){var e=['r','g','b','a'];var x=4;while(-1<--x){this[e[x]]=arguments[x]||((x==3)?1.0:0)}this.toString=function(){if(this.a>=1.0){return"rgb("+[this.r,this.g,this.b].join(",")+")"}else{return"rgba("+[this.r,this.g,this.b,this.a].join(",")+")"}};this.scale=function(a,b,c,d){x=4;while(-1<--x){if(arguments[x]!=null)this[e[x]]*=arguments[x]}return this.normalize()};this.adjust=function(a,b,c,d){x=4;while(-1<--x){if(arguments[x]!=null)this[e[x]]+=arguments[x]}return this.normalize()};this.clone=function(){return new Color(this.r,this.b,this.g,this.a)};var f=function(a,b,c){return Math.max(Math.min(a,c),b)};this.normalize=function(){this.r=clamp(0,parseInt(this.r),255);this.g=clamp(0,parseInt(this.g),255);this.b=clamp(0,parseInt(this.b),255);this.a=clamp(0,this.a,1);return this};this.normalize()}var D={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0]};function extractColor(a){var b,elem=a;do{b=elem.css("background-color").toLowerCase();if(b!=''&&b!='transparent')break;elem=elem.parent()}while(!$.nodeName(elem.get(0),"body"));if(b=="rgba(0, 0, 0, 0)")return"transparent";return b}function parseColor(a){var b;if(b=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(a))return new Color(parseInt(b[1],10),parseInt(b[2],10),parseInt(b[3],10));if(b=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(a))return new Color(parseInt(b[1],10),parseInt(b[2],10),parseInt(b[3],10),parseFloat(b[4]));if(b=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(a))return new Color(parseFloat(b[1])*2.55,parseFloat(b[2])*2.55,parseFloat(b[3])*2.55);if(b=/rgba\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(a))return new Color(parseFloat(b[1])*2.55,parseFloat(b[2])*2.55,parseFloat(b[3])*2.55,parseFloat(b[4]));if(b=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(a))return new Color(parseInt(b[1],16),parseInt(b[2],16),parseInt(b[3],16));if(b=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(a))return new Color(parseInt(b[1]+b[1],16),parseInt(b[2]+b[2],16),parseInt(b[3]+b[3],16));var c=$.trim(a).toLowerCase();if(c=="transparent")return new Color(255,255,255,0);else{b=D[c];return new Color(b[0],b[1],b[2])}}})(jQuery);