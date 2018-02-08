var FLIPBOOK = FLIPBOOK || {};

{/* FLIPBOOK.PageWebGL */
FLIPBOOK.PageWebGL = function (book, i, matf, matb, hard, options, preloader, geometry) {
	this.book = book;
	this.preloader = preloader;
	this.frontTexture = matf;
	this.backTexture = matb;
	this.index = i;
	this.materialType = options.pageMaterial;
	this.pW = options.pageWidth;
	this.pH = options.pageHeight;
	this.pageShininess = options.pageShininess;
	this.nfacesw = options.pageSegmentsW;
	this.nfacesh = options.pageSegmentsH;
	this.mats = [];
	this.pageHardness = hard;
	//	this.pageThickness=1 + hard/10;
	this.pageThickness = hard;
	//    this.pageThickness= 20;
	this.duration = options.pageFlipDuration;
	this.angle = .25 * Math.PI * this.pW / this.pH;
	this.force = 10;
	this.offset = 0;
	this.to = null;
	this.mod = null;
	this.bend = null;
	this.pivot = null;
	this.isFlippedLeft = false;
	this.isFlippedRight = true;
	this.flippingLeft = false;
	this.flippingRight = false;
	this.zz = 2;
	this.sides = {
		bottom : 3,
		top : 2,
		right : 0,
		left : 1,
		front : 4,
		back : 5
	};
	// this.overdraw = 1;

	var map;
	for (var mii = 0; mii < 6; mii++) {
		// add front - back page images
		if (mii == this.sides.front) {
			// this.mats[this.sides.front] = this.createMaterial(this.materialType, this.frontTexture);
			this.mats[this.sides.front] = this.createEmptyMaterial(this.materialType);
		} else if (mii == this.sides.back) {
			// this.mats[this.sides.back] = this.createMaterial(this.materialType, this.backTexture);
			this.mats[this.sides.back] = this.createEmptyMaterial(this.materialType);
		} else {
			//random gray
			var r = parseInt(Math.random() * 5);
			var a = [0x777777, 0x999999, 0xaaaaaa, 0xdddddd, 0xffffff];
			var c = a[r];
			this.mats[mii] = new THREE.MeshBasicMaterial({
					color : c
				});
		}
	}
	
	THREE.Mesh.call(this, new THREE.BoxGeometry(this.pW, this.pH, .1, this.nfacesw, this.nfacesh, 1), new THREE.MeshFaceMaterial(this.mats));
	this.overdraw = false;

	//TODO bug with shadows - if this.castShadow = true; the pages have shadows always
	// this.castShadow = false;
	// this.receiveShadow = false;
	
	//page initial positioning
	
	this.position.x = this.pW * 0.5;
	// var th = 4
	// if (this.index > 0)
		// this.position.z = this.book.pages[this.index - 1].position.z - th / 2 - th / 2 - 1;
	// else
		// this.position.z = th / 2;
		
	// flip modifiers
	this.mod = new MOD3.ModifierStack(new MOD3.LibraryThree(), this);
	this.pivot = new MOD3.Pivot(this.position.x, 0, 0);
	this.mod.addModifier(this.pivot);
	this.mod.collapse();

	this.bend = new MOD3.Bend(0, 0, 0);
	this.bend.constraint = MOD3.ModConstant.LEFT;
	if (this.pH > this.pW)
		this.bend.switchAxes = true;
	this.mod.addModifier(this.bend);
};
FLIPBOOK.PageWebGL.prototype = new THREE.Mesh();
FLIPBOOK.PageWebGL.prototype.constructor = FLIPBOOK.PageWebGL;

FLIPBOOK.PageWebGL.prototype.load = function () {
	if (this.loaded)
		return;
	var texture, self=this;
	var options = this.book.options, main = options.main;
	if(options.pdfUrl != "" && this.loadedFromPdf != true){
		this.loadFromPdf();
		//this.loadedFromPdf = true;
		return;
	}	
	
	texture = THREE.ImageUtils.loadTexture(self.frontTexture, undefined, function(){
		//front loaded
		self.mats[self.sides.front] = self.createMaterial(self.materialType, texture);
		//load back
		texture = THREE.ImageUtils.loadTexture(self.backTexture, undefined, function(){
			//back loaded
			self.mats[self.sides.back] = self.createMaterial(self.materialType, texture);
			self.material = new THREE.MeshFaceMaterial(self.mats);
			self.loaded = true;
		});
	});
	
};

FLIPBOOK.PageWebGL.prototype.loadFromPdf = function () {
	var options = this.book.options, main = options.main, self = this;
	if(options.rightToLeft){
		var index = this.book.pages.length - this.index - 1;
		 main.loadPagesFromPdf([index*2, index*2+1], function(){
			self.loadedFromPdf = true;
			self.frontTexture = options.pages[2*self.index].src;
			self.backTexture = options.pages[2*self.index+1].src;
			self.load()
		})
	}else{
		main.loadPagesFromPdf([this.index*2, this.index*2+1], function(){
			self.loadedFromPdf = true;
			self.frontTexture = options.pages[2*self.index].src;
			self.backTexture = options.pages[2*self.index+1].src;
			self.load()
		})
	}
	
};

FLIPBOOK.PageWebGL.prototype.createEmptyMaterial = function (type) {
	var mat;
	if (type == 'lambert') {
		mat = new THREE.MeshLambertMaterial({
				// color: 0xeeeeee
				map : this.preloader,
				overdraw : false,
				ambient : 0xdddddd,
				specular : 0xffffff,
				diffuse : 0xffffff,
				shininess : 20,
				perPixel : true //???
			});
	} else if (type == 'phong') {
		mat = new THREE.MeshPhongMaterial({
				// color: 0xeeeeee
				map : this.preloader,
				overdraw : false,
				ambient : 0xdddddd,
				specular : 0xffffff,
				diffuse : 0xffffff,
				shininess : 20,
				perPixel : true //???
			});
	} else if (type == 'basic') {
		mat = new THREE.MeshBasicMaterial({
				// color: 0xeeeeee
				map : this.preloader,
				overdraw : false,
				perPixel : true //???
			});
	}
	// mat = new THREE.MeshBasicMaterial({color: 0xeeeeee});
	return mat;
};
FLIPBOOK.PageWebGL.prototype.createMaterial = function (type, map) {
	var mat;

	if (type == 'lambert') {
		mat = new THREE.MeshLambertMaterial({
				map : map,
				overdraw: false,
				ambient : 0xdddddd,
				specular : 0xffffff,
				diffuse : 0xffffff,
				shininess : 20,
				perPixel : false //???
			});
	} else if (type == 'phong') {
		mat = new THREE.MeshPhongMaterial({
				map : map,
				overdraw: false,
				ambient : 0xdddddd,
				specular : 0xffffff,
				diffuse : 0xffffff,
				shininess : 20,
				perPixel : false //???
			});

		// mat = new THREE.MeshBasicMaterial( { color: 0x00ee00, wireframe: true, transparent: true } );
	} else if (type == 'basic') {
		mat = new THREE.MeshBasicMaterial({
				map : map,
				overdraw: false,
				perPixel : false //???
			});
	}
	return mat;
};
FLIPBOOK.PageWebGL.prototype.flipLeft = function (onComplete) {
	this.onComplete = onComplete;
	if (!this.isFlippedLeft && !this.flippingLeft && !this.flippingRight && this.index == this.book.flippedleft) {
		if (this.duration > 0) {
			this.flippingLeft = true;
			this.flipping = true;
			this.force = 0;
			var newForce = (1 + Math.random() * .5) / this.pageHardness;
			var newOffset = .1 + Math.random() * .2;
			this.to = {
				angle : this.rotation.y,
				t : -1,
				xx : 0,
				thiss : this,
				force : this.force,
				offset : this.offset
			};
			this.bendIn(-Math.PI, newForce, newOffset);
		} else {

			this.rotation.y = -Math.PI;
			this.flippingLeft = false;
			this.isFlippedLeft = true;
			this.flippingRight = false;
			this.isFlippedRight = false;
		}

		var th = 4
		//correct z order
		this.position.z = th / 2 + 1;
		//left side
		for (var i = this.index - 1; i >= 0; i--) {
			this.book.pages[i].position.z = this.book.pages[i + 1].position.z - th / 2 - th / 2 - 1;
		}
		//right side
		for (var i = this.index + 1; i < this.book.pages.length; i++) {
			this.book.pages[i].position.z = this.book.pages[i - 1].position.z - th / 2 - th / 2 - 1;
		}
	}
};

FLIPBOOK.PageWebGL.prototype.flipLeftInstant = function (onComplete) {
	this.onComplete = onComplete;
	if (!this.isFlippedLeft && !this.flippingLeft && !this.flippingRight && this.index == this.book.flippedleft) {

		this.thiss = this;
		this.xx = 0;
		this.angle = -Math.PI;
		this.flippingLeft = true;
		this.isFlippedLeft = false;
		this.renderFlip();
		this.flippingLeft = false;
		this.isFlippedLeft = true;
		this.flippingRight = false;
		this.isFlippedRight = false;
		
		var th = 4
		//correct z order
		this.position.z = th / 2 + 1;
		

		//right side
		for (var i = this.index + 1; i < this.book.pages.length; i++) {
			this.book.pages[i].position.z = this.book.pages[i - 1].position.z - th / 2 - th / 2 - 1;
		}
		
		//left side
		if(this.index < this.book.pages.length-1){
			this.position.z = this.book.pages[this.index+1].position.z;
			for (var i = this.index - 1; i >= 0; i--) {
				this.book.pages[i].position.z = this.book.pages[i + 1].position.z - th / 2 - th / 2 - 1;
			}
		}else{
			if(this.index > 0)
			this.book.pages[this.index].position.z = this.book.pages[this.index - 1].position.z + th / 2 + th / 2 - 1;
		}
		
		this.flipFinished()
	}
};


FLIPBOOK.PageWebGL.prototype.flipRightInstant = function (onComplete) {
	this.onComplete = onComplete;
	if (!this.isFlippedRight && !this.flippingRight && !this.flippingLeft && this.index == this.book.getNumPages() - this.book.flippedright - 1) {

		this.thiss = this;
		this.xx = 0;
		this.angle = 0;
		this.flippingRight = true;
		this.isFlippedRight = false;
		this.renderFlip();
		this.flippingLeft = false;
		this.isFlippedLeft = false;
		this.flippingRight = false;
		this.isFlippedRight = true;
		
		var th = 4
		//correct z order
		this.position.z = th / 2 + 1;
		
		//left side
		for (var i = this.index - 1; i >= 0; i--) {
			this.book.pages[i].position.z = this.book.pages[i + 1].position.z - th / 2 - th / 2 - 1;
		}
		
		//right side
		if(this.index > 0){
			this.position.z = this.book.pages[this.index-1].position.z;
			for (var i = this.index + 1; i < this.book.pages.length; i++) {
				this.book.pages[i].position.z = this.book.pages[i - 1].position.z - th / 2 - th / 2 - 1;
			}
		}else{
			if(this.book.pages.length > 1)
			this.position.z = this.book.pages[this.index + 1].position.z + th / 2 + th / 2 - 1;
		}
		
		this.flipFinished()
	}
};


FLIPBOOK.PageWebGL.prototype.flipRight = function (onComplete) {
	this.onComplete = onComplete;
	if (!this.isFlippedRight && !this.flippingRight && !this.flippingLeft && this.index == this.book.getNumPages() - this.book.flippedright - 1) {

		if (this.duration > 0) {
			this.flippingRight = true;
			this.flipping = true;
			this.force = 0;
			this.to = {
				angle : this.rotation.y,
				t : -1,
				xx : 0,
				thiss : this,
				force : this.force,
				offset : this.offset
			};
			var newForce = (-1 - Math.random() * .5) / this.pageHardness;
			var newOffset = .1 + Math.random() * .2;
			this.bendIn(0, newForce, newOffset);
		} else {
			this.rotation.y = 0;
			this.flippingLeft = false;
			this.isFlippedLeft = false;
			this.flippingRight = false;
			this.isFlippedRight = true;
		}

		var th = 4
		//
		//correct z order
		this.position.z = th / 2 + 1;

		//right side
		for (var i = this.index + 1; i < this.book.pages.length; i++) {
			this.book.pages[i].position.z = this.book.pages[i - 1].position.z - th / 2 - th / 2 - 1;
		}
		//left side
		for (var i = this.index - 1; i >= 0; i--) {
			this.book.pages[i].position.z = this.book.pages[i + 1].position.z - th / 2 - th / 2 - 1;
		}

	}
};
FLIPBOOK.PageWebGL.prototype.bendIn = function (angle, newForce, newOffset) {
	//random bend angle
	//    this.bend.setAngle(Math.random() * .25 - .5);
	this.bend.setAngle(Math.random() * (Math.PI / 24) - (Math.PI / 48));
	this.bend.force = 0.0;
	this.bend.offset = 0.0;
	this.mod.apply();

	var time1 = this.duration * (400 + this.pageHardness * 5);
	var time2 = this.duration * (300 + this.pageHardness * 5);

	//tween page rotation Y
	new TWEEN.Tween(this.to).to({
		angle : angle,
		xx : 1,
		t : 1
	}, time1)
	.easing(TWEEN.Easing.Sinusoidal.EaseInOut)
	.onUpdate(this.renderFlip)
	// .onComplete(this.bendOut)
	.start();
	//tween bend.force
	new TWEEN.Tween(this.to).to({
		force : newForce
	}, time2)
	.easing(TWEEN.Easing.Quadratic.EaseInOut)
	.onUpdate(this.modApply)
	.onComplete(this.bendOut)
	.start();
	//tween bend.offset
	new TWEEN.Tween(this.to).to({
		offset : newOffset
	}, time2)
	.easing(TWEEN.Easing.Quadratic.EaseOut)
	.onUpdate(this.modApply)
	.start();
	
	this.book.options.main.playFlipSound();

};
FLIPBOOK.PageWebGL.prototype.bendOut = function () {
	//tween bend.force to 0
	var self = this.thiss;
	var time = self.duration * (700 - self.pageHardness * 50);

	//tween bend.offset to 1
	new TWEEN.Tween(self.to).to({
		force : 0,
		offset : 1
	}, time)
	.easing(TWEEN.Easing.Sinusoidal.EaseOut)
	.onUpdate(self.modApply)
	.onComplete(self.flipFinished)
	.start();
	
	var th = 4
	if(self.flippingLeft){
		if(self.index < self.book.pages.length-1){
			self.position.z = self.book.pages[self.index+1].position.z;
			for (var i = self.index - 1; i >= 0; i--) {
				self.book.pages[i].position.z = self.book.pages[i + 1].position.z - th / 2 - th / 2 - 1;
			}
		}else{
			if(self.book.pages.length > 1)
				self.book.pages[self.index].position.z = self.book.pages[self.index - 1].position.z + th / 2 + th / 2 - 1;
		}
	}
	if(self.flippingRight){
		if(self.index > 0){
			self.position.z = self.book.pages[self.index-1].position.z;
			for (var i = self.index + 1; i < self.book.pages.length; i++) {
				self.book.pages[i].position.z = self.book.pages[i - 1].position.z - th / 2 - th / 2 - 1;
			}
		}else{
			if(self.book.pages.length > 1)
			self.position.z = self.book.pages[self.index + 1].position.z + th / 2 + th / 2 - 1;
		}
	}
	
};
FLIPBOOK.PageWebGL.prototype.modApply = function () {
	this.thiss.bend.force = this.force;
	this.thiss.bend.offset = this.offset;
	this.thiss.mod.apply();
};
FLIPBOOK.PageWebGL.prototype.renderFlip = function () {
	var p2 = Math.PI * 0.5;
	// align flipBook to center
	if (this.thiss.flippingLeft && this.thiss.index == 0 && this.thiss.book.getNumPages() > 1)
		this.thiss.book.centerContainer.position.x = (1 - this.xx) * this.thiss.book.centerContainer.position.x;
	else if (this.thiss.flippingLeft && this.thiss.index == this.thiss.book.getNumPages() - 1)
		this.thiss.book.centerContainer.position.x = (1 - this.xx) * this.thiss.book.centerContainer.position.x + this.xx * this.thiss.book.pageW * 0.5;
	else if (this.thiss.flippingRight && this.thiss.index == 0)
		this.thiss.book.centerContainer.position.x = (1 - this.xx) * this.thiss.book.centerContainer.position.x - this.xx * this.thiss.book.pageW * 0.5;
	else if (this.thiss.flippingRight && this.thiss.index == this.thiss.book.getNumPages() - 1)
		this.thiss.book.centerContainer.position.x = (1 - this.xx) * this.thiss.book.centerContainer.position.x;

	//apply new angle
	this.thiss.rotation.y = this.angle;
};
FLIPBOOK.PageWebGL.prototype.flipFinished = function () {
	var self = this.thiss;
	if (self.flippingLeft) {
		self.flippingLeft = false;
		self.isFlippedLeft = true;
		self.flippingRight = false;
		self.isFlippedRight = false;

	} else if (self.flippingRight) {
		self.flippingLeft = false;
		self.isFlippedRight = true;
		self.flippingRight = false;
		self.isFlippedLeft = false;
	}
	self.bend.force = 0.0;
	//    this.thiss.bend.setAngle(0.0);
	self.bend.offset = 0.0;
	self.mod.apply();
	self.flipping = false;
	if(typeof(self.onComplete) != 'undefined')
		self.onComplete(self);
	self.book.flipFinnished();
	
	self.book.options.main.turnPageComplete()
};
FLIPBOOK.PageWebGL.prototype.isFlippedLeft = function () {
	return this.thiss.isFlippedLeft;
};
FLIPBOOK.PageWebGL.prototype.isFlippedRight = function () {
	return this.thiss.isFlippedRight;
};
}

{/* FLIPBOOK.BookWebGL */
FLIPBOOK.BookWebGL = function (el, options) {
	this.elementId = jQuery(options.main.elem).attr("id")
    this.wrapper = typeof el == 'object' ? el : document.getElementById(el);
    this.options = options;
    this.pageW = options.pageWidth;
    this.pageH = options.pageHeight;
    this.scroll = options.scroll;
    this.pagesArr = options.pagesArr;
    this.pages = [];
    this.animating = false;
    this.rightIndex = 0;
    this.sc = 1;

    var s = this.wrapper.style;

    s.width = '100%';
    s.height = '100%';
    s.position = 'absolute';
    s.overflow = 'hidden';

    
	if(!options.lightBox || options.lightBoxOpened){
		this.enable();
	}
		

};
FLIPBOOK.BookWebGL.prototype=new THREE.Object3D();
FLIPBOOK.BookWebGL.prototype.constructor = FLIPBOOK.BookWebGL;
FLIPBOOK.BookWebGL.prototype.init3d = function(){
    // WebGL starts here
    var self = this,
        VIEW_ANGLE = 30,
        w = jQuery(self.wrapper).width(),
        h = jQuery(self.wrapper).height(),
        ASPECT = w/h,
        NEAR = 1,
        FAR = 10000,
        o = this.options;

    self.clock = new THREE.Clock();

    //scene
    self.scene = new THREE.Scene();
    self.camera = new THREE.PerspectiveCamera( VIEW_ANGLE, ASPECT, NEAR, FAR);
    self.scene.add(self.camera);
    self.zoom = o.zoom;
    self.pan = o.pan;
    self.tilt = o.tilt;

    this.updateCameraPosition();
	
	// this.stats = new Stats();
	// this.wrapper.appendChild( this.stats.domElement );

    //renderer
    this.canvas = document.createElement('canvas');
	jQuery(this.canvas).attr("id",Math.random());
    var s = this.canvas.style;
    s.width = '100%';
    s.height = '100%';
    s.position = 'absolute';
    s.left = '0';
    s.top = '0';

    this._bind('mousedown', this.canvas);
    this._bind('mousemove', this.canvas);
    this._bind('mouseout',this.canvas);
    this._bind('mouseup', this.canvas);
	
    this._bind('touchstart', this.canvas);
    this._bind('touchmove', this.canvas);
    this._bind('touchend', this.canvas);
    this._bind('touchcancel', this.canvas);

    //antialias : true makes no difference
	
	if(self.options.renderer == "webgl"){
		self.renderer = new THREE.WebGLRenderer({alpha:true, antialias: true, canvas:this.canvas});
		self.renderer.setClearColor(0x00ffffff, 0);
		self.renderer.setClearColor( 0x000000, 0 ); 
	}else if(self.options.renderer == "canvas"){
		self.renderer = new THREE.CanvasRenderer({antialias: true,canvas:this.canvas});
	}else if(self.options.renderer == "css3d"){
		// self.renderer = new THREE.CSS3DRenderer();
		// self.renderer = new THREE.SoftwareRenderer();
	}
    self.renderer.setSize( w, h );

    if(o.pageShadow){
        // self.renderer.shadowMapEnabled = true;
//        renderer.sortObjects = true;
        // self.renderer.shadowMapWidth = 1024;
        // self.renderer.shadowMapHeight = 1024;
    }

    // bind the resize event
    jQuery(window).resize(function () {
        self.onResize();
    });

    // point light
    if(o.pointLight == true){
        var pl= new THREE.PointLight(o.pointLightColor);
        pl.position.set(o.pointLightX,o.pointLightY,o.pointLightZ);
        pl.intensity = o.pointLightIntensity;
        self.scene.add(pl);
    }

    // directional light
    if(o.directionalLight == true){
        var dl	= new THREE.DirectionalLight( o.directionalLightColor );
        dl.position.set(o.directionalLightX,o.directionalLightY,o.directionalLightZ);
        dl.intensity = o.directionalLightIntensity;
        self.scene.add(dl);
    }

    // ambient light
    if(o.ambientLight == true){
        var al = new THREE.AmbientLight(o.ambientLightColor);
        al.intensity = o.ambientLightIntensity;
        self.scene.add(al);
    }

    // spotlight
    if(o.spotLight){
        var sl	= new THREE.SpotLight( o.spotLightColor);
        sl.position.z = o.spotLightZ;
        sl.intensity = o.spotLightIntensity;
        // sl.shadowMapWidth = 1024;
        // sl.shadowMapHeight = 1024;
        // sl.shadowCameraNear= o.spotLightShadowCameraNear;
        // sl.shadowCameraFar = o.spotLightShadowCameraFar;
        // sl.castShadow = o.spotLightCastShadow;
        // sl.shadowDarkness = o.spotLightShadowDarkness;
        self.scene.add( sl );
    }

    self.scene.add(this);

    this.position.set(o.bookX, o.bookY, o.bookZ);

    this.centerContainer=new THREE.Object3D();
    this.add(this.centerContainer);

    // align flipBook center container
    this.centerContainer.position.x=-this.pageW*0.5;
	if(this.options.rightToLeft){
		this.centerContainer.position.x=this.pageW*0.5;
	}

    this.flippedleft=0;
    this.flippedright=0;

    this.cameraZMin = 300;
    this.cameraZMax = 5000;
	
	//this.initialized = true;
};
FLIPBOOK.BookWebGL.prototype.onResize = function(){
    var w = jQuery(this.wrapper).width(),
        h = jQuery(this.wrapper).height();
    if(h>w){
        this.sc = w/h;
    }else{
        this.sc = 1;
    }
	
	if(w/h < 2*this.options.pageWidth / this.options.pageHeight){
		
		this.sc /= (2*this.options.pageWidth / this.options.pageHeight)/(w/h)
	}
//    this.zoomTo();
//    this.updateCameraPosition();

    // notify the renderer of the size change
    this.renderer.setSize( w, h);
    // update the camera
    this.camera.aspect	= w/h;
    this.camera.updateProjectionMatrix();

    this.updateCameraPosition();
};
FLIPBOOK.BookWebGL.prototype.updateCameraPosition = function(){
    //tilt
    var angle = Math.PI * this.tilt / 180;
    var cameraX = 0;
    var cameraY = this.options.cameraDistance*Math.sin(angle) / (this.zoom);
    var cameraZ = this.options.cameraDistance*Math.cos(angle) / (this.zoom );

    this.scale.set(this.sc,this.sc,this.sc);
	
    //pan
    angle = Math.PI * this.pan / 180;
    cameraX = Math.sin(angle) * cameraZ;
    cameraZ = Math.cos(angle) * cameraZ;

    this.camera.position.set(Math.round(cameraX),Math.round(cameraY),Math.round(cameraZ));
    this.camera.lookAt(this.scene.position);
};
FLIPBOOK.BookWebGL.prototype.createPages = function(){
    //create all pages
	var self = this;
	var texturefront, textureback, hardness, page, i;
	var preloader = THREE.ImageUtils.loadTexture(this.options.assets.preloader, undefined, function(){
		for (i=0;i<self.pagesArr.length/2;i++)
		{
			texturefront = self.pagesArr[2*i].src;
			textureback = (2*i+1 >= self.pagesArr.length) ? "" : self.pagesArr[2*i + 1].src;
			hardness = (i == 0 || i == (self.pagesArr.length/2-1))? self.options.coverHardness : self.options.pageHardness;
			page = new FLIPBOOK.PageWebGL(self,i,texturefront,textureback,hardness,self.options, preloader/*, geometry*/);
			self.pages.push(page);
			self.centerContainer.add(page);
			self.flippedright++;
			
			if(self.options.loadAllPages)
				page.load()
				
			var th = 4
			if (i > 0)
				page.position.z = self.pages[i - 1].position.z - th / 2 - th / 2 - 1;
			else
				page.position.z = th / 2;
		}
		
		self.initialized = true;
		
		if(self.options.rightToLeft){
			self.pages[self.pages.length-1].load();
			if(self.pages.length > 1)
				self.pages[self.pages.length-2].load();
		}else{
			self.pages[0].load();
			if(self.pages.length > 1)
				self.pages[1].load();	
		}
		
	});

    
	
	
	
	//custom
	/*
	var rendererCSS	= new THREE.CSS3DRenderer();
	rendererCSS.setSize( window.innerWidth, window.innerHeight );
	rendererCSS.domElement.style.position	= 'absolute';
	rendererCSS.domElement.style.top	= 0;
	rendererCSS.domElement.style.margin	= 0;
	rendererCSS.domElement.style.padding	= 0;
	document.body.appendChild( rendererCSS.domElement );

	THREEx.WindowResize.bind(rendererCSS, world.camera().get(0));		

	// put the mainRenderer on top
	var rendererMain	= world.tRenderer();
	rendererMain.domElement.style.position	= 'absolute';
	rendererMain.domElement.style.top	= 0;
	rendererMain.domElement.style.zIndex	= 1;
	rendererCSS.domElement.appendChild( rendererMain.domElement );

	// var element	= document.createElement('iframe')
	// element.src	= 'http://learningthreejs.com'
	// element.style.width = '1024px';
	// element.style.height = '1024px';

	var element = document.createElement( 'div' );
	element.style.width = '100px';
	element.style.height = '100px';
	element.style.background = new THREE.Color( Math.random() * 0xffffff ).getStyle();

	var sceneCSS	= new THREE.Scene();
	var objectCSS 	= new THREE.CSS3DObject( element );
	window.objectCSS	= objectCSS
	objectCSS.scale.multiplyScalar(1/63.5)
	sceneCSS.add( objectCSS );
	
	*/
	
	
	
};
FLIPBOOK.BookWebGL.prototype.getNumPages = function(){
    return(this.pages.length);
};
FLIPBOOK.BookWebGL.prototype.centerContainer = function(){
    return(this.centerContainer);
};
FLIPBOOK.BookWebGL.prototype.goToPage = function(index,instant){ //index in book.pages, not page number

	if(!this.initialized){
		// console.log("initializing...")
		var self = this;
		setTimeout(function(){self.goToPage(index, instant)}, 100)
		return;
	}
		
    this.goingToPage = index;

    index % 2 != 0 ? index++ : index;

    var self = this;

	var delay = this.options.pageFlipDuration*1000/8;
	
	if(typeof(instant) != 'undefined' && instant){
		
		// delay = 0;
		// for(var i=0;i<self.pages.length;i++	){
			// self.pages[i].duration = 0;
		// }
		if(index > self.rightIndex){
			while(self.rightIndex < index)
			this.nextPageInstant()
		}else{
			while(self.rightIndex > index)
			this.prevPageInstant()
			
		}
		
			
		
			
		var rightPage = this.pages[this.flippedleft];
		if(rightPage) rightPage.load()
		var leftPage = this.pages[this.flippedleft-1];
		if(leftPage) leftPage.load()
		return;
	}

    if(this.rightIndex > index){
	
		delay = 1/(this.rightIndex - index) * this.options.pageFlipDuration*1000/8;
        if(this.rightIndex - index > 10) delay = 0
		
        if(this.rightIndex -2 > index){
			this.prevPage(false);
			if(delay > 0 )
				setTimeout(function(){self.goToPage(index,instant)}, delay);
			else	
				self.goToPage(index,instant);
        }else{
			this.prevPage();
			setTimeout(function(){
				if(typeof(instant) != 'undefined' && instant){
					for(var i=0;i<self.pages.length;i++	){
						self.pages[i].duration = self.options.pageFlipDuration;
					}
				}
				self.options.main.turnPageComplete()
			}, delay);
		}
    }else if(this.rightIndex < index){
	
		delay = -1/(this.rightIndex - index) * this.options.pageFlipDuration*1000/8;
        if(this.rightIndex - index < -10) delay = 0
		
        if((this.rightIndex + 2 ) < index){
			this.nextPage(false);
            if(delay > 0 )
				setTimeout(function(){self.goToPage(index,instant)}, delay);
			else	
				self.goToPage(index,instant);
        }else{
			this.nextPage();
			setTimeout(function(){
				if(typeof(instant) != 'undefined' && instant){
					for(var i=0;i<self.pages.length;i++	){
						self.pages[i].duration = self.options.pageFlipDuration;
					}
				}
				self.options.main.turnPageComplete()
			}, delay);
		}
    }
};
FLIPBOOK.BookWebGL.prototype.nextPageInstant = function(load){
	if(this.flippedright == 0)
        return;
//    if flipping in opposite direction already - return
    var i;
    for(i = 0; i <this.pages.length; i++){
        if(this.pages[i].flippingRight)
            return;
    }

    var page = this.pages[this.pages.length-this.flippedright];

	page.flipLeftInstant(); 
	this.flippedleft++;
    this.flippedright--;
	this.rightIndex += 2;
	this.options.main.turnPageComplete()
	this.updateBookPosition()
};
FLIPBOOK.BookWebGL.prototype.prevPageInstant = function(load){
	if(this.flippedleft == 0)
        return;

    var i;
    for(i = 0; i <this.pages.length; i++){
        if(this.pages[i].flippingLeft)
            return;
    }

    var page = this.pages[this.flippedleft-1];
	
	page.flipRightInstant(); 
	this.flippedleft--;
    this.flippedright++;
	this.rightIndex -= 2;
	this.options.main.turnPageComplete()
	this.updateBookPosition()
};
FLIPBOOK.BookWebGL.prototype.nextPage = function(load){
	// this.nextPageInstant();return;
	
    if(this.flippedright == 0)
        return;
//    if flipping in opposite direction already - return
    var i;
    for(i = 0; i <this.pages.length; i++){
        if(this.pages[i].flippingRight)
            return;
    }

    var page = this.pages[this.pages.length-this.flippedright];
	
	var rightPage = this.pages[this.flippedleft+1];
	var leftPage = this.pages[this.flippedleft];

    if(!page.flipping){
		var self = this, onComplete;
		if(typeof(load) == 'undefined' || load ){
			onComplete = function(page){
				var rightPage = self.pages[self.flippedleft];
				var leftPage = self.pages[self.flippedleft-1];
				var prevPage = self.pages[self.flippedleft-2];
				var nextPage = self.pages[self.flippedleft+1];
				if(rightPage)
					rightPage.load();
				if(leftPage)
					leftPage.load();
				if(prevPage)
					prevPage.load();
				if(nextPage)
					nextPage.load();
			}
		}
			
		page.flipLeft(onComplete);
    }
	this.flippedleft++;

    this.flippedright--;
    this.rightIndex += 2;

    // this.options.parent.updateCurrentPage();
};
FLIPBOOK.BookWebGL.prototype.updateBookPosition = function(){
	if(this.rightIndex == 0)
		this.centerContainer.position.x = -this.options.pageWidth/2
	else if(this.rightIndex == this.options.pages.length)
		this.centerContainer.position.x = this.options.pageWidth/2
	else
		this.centerContainer.position.x = 0
};
FLIPBOOK.BookWebGL.prototype.prevPage = function(load){

	// this.prevPageInstant();return;
    if(this.flippedleft == 0)
        return;

    var i;
    for(i = 0; i <this.pages.length; i++){
        if(this.pages[i].flippingLeft)
            return;
    }

    var page = this.pages[this.flippedleft-1];
	
	var rightPage = this.pages[this.flippedleft-1];
	var leftPage = this.pages[this.flippedleft-2];
	/*if(typeof(load) == 'undefined' || load ){	
		if(rightPage)
			rightPage.load();
		if(leftPage)
			leftPage.load();
	}	*/
	
	
    if(!page.flipping){
		var self = this, onComplete;
		if(typeof(load) == 'undefined' || load ){
			onComplete = function(page){
				var rightPage = self.pages[self.flippedleft];
				var leftPage = self.pages[self.flippedleft-1];
				var prevPage = self.pages[self.flippedleft-2];
				var nextPage = self.pages[self.flippedleft+1];
				if(rightPage)
					rightPage.load();
				if(leftPage)
					leftPage.load();
				if(prevPage)
					prevPage.load();
				if(nextPage)
					nextPage.load();
			}
		}
		page.flipRight(onComplete);
	}
    this.flippedleft--;
    this.flippedright++;
    this.rightIndex -= 2;

    // this.options.parent.updateCurrentPage();
};
FLIPBOOK.BookWebGL.prototype.firstPage = function(){

};
FLIPBOOK.BookWebGL.prototype.flipFinnished = function(){
	// this.pages[this.flippedleft].load();
	// this.pages[this.flippedleft+1].load();
	// console.log("flip finnished");
	// this.pages[1].load();
};
FLIPBOOK.BookWebGL.prototype.lastPage = function(){

};
FLIPBOOK.BookWebGL.prototype.updateVisiblePages = function(){

};
FLIPBOOK.BookWebGL.prototype.render = function(rendering) {
    var self = this;
    self.rendering = rendering;
    //rendering ? self.wrapper.appendChild(self.renderer.domElement) : self.wrapper.removeChild(self.renderer.domElement);
	
	if(!rendering)
		jQuery(self.wrapper).find('canvas').remove()
	else
		self.wrapper.appendChild(self.renderer.domElement)

    self.animate = function(){
        if(self.rendering){
            
			if(!self.enabled) {
				return;
			}
			
			requestAnimationFrame(self.animate);
			
            TWEEN.update();
            self.renderer.render(self.scene, self.camera);
			// if(self.stats)
				// self.stats.update();
			// console.log("rendering..."+self.elementId)
        }
    }

    self.animate();
};
FLIPBOOK.BookWebGL.prototype.zoomTo = function(amount,time) {
    // if(this.zooming)
        // return;
	if(typeof(time) === 'undefined')
		time = 200;
	var self = this;
    newZoom = amount > this.options.zoomMax ? this.options.zoomMax : amount;
    newZoom = amount < this.options.zoomMin ? this.options.zoomMin : amount;
    this.zooming = true;
	
	if(newZoom == this.options.zoom){
		//reset book position
		var o = this.options;
		this.position.set(o.bookX, o.bookY, o.bookZ);
	}
		
	if(time > 0){
		new TWEEN.Tween(this).to({zoom:newZoom}, time)
        .easing( TWEEN.Easing.Sinusoidal.EaseInOut)
        .onUpdate(this.updateCameraPosition)
        .onComplete(function(){self.zooming = false})
        .start();
	}else{
		this.zoom = newZoom
		this.zooming = false
		this.updateCameraPositio
	}
    
	self.options.parent.onZoom(newZoom)
	
	
};
FLIPBOOK.BookWebGL.prototype.tiltTo = function(amount) {
//    if(this.tilting)
//        return;
    var self = this, factor = .3;
    var newTilt = this.tilt + amount*factor;
    newTilt = newTilt > this.options.tiltMax ? this.options.tiltMax : newTilt;
    newTilt = newTilt < this.options.tiltMin ? this.options.tiltMin : newTilt;

    this.tilt = newTilt;
    this.updateCameraPosition();

//    this.tilting = true;
//    new TWEEN.Tween(this).to({tilt:newTilt}, 400)
//        .easing( TWEEN.Easing.Sinusoidal.EaseInOut)
//        .onUpdate(this.updateCameraPosition)
//        .onComplete(function(){self.tilting = false})
//        .start();
};
FLIPBOOK.BookWebGL.prototype.panTo = function(amount) {
//    if(this.tilting)
//        return;
    var self = this, factor = .2;
    var newPan = this.pan - amount*factor;
    newPan = newPan > this.options.panMax ? this.options.panMax : newPan;
    newPan = newPan < this.options.panMin ? this.options.panMin  : newPan;

    this.pan = newPan;
    this.updateCameraPosition();
};
FLIPBOOK.BookWebGL.prototype._bind = function (type, el, bubble) {
    (el || this.wrapper).addEventListener(type, this, !!bubble);
};
FLIPBOOK.BookWebGL.prototype.handleEvent = function (e) {
    var self = this;
	e.preventDefault()
//console.log(e);
    switch (e.type) {
        case 'mousedown':
			self._start(e);
			break;
        case 'touchstart':
            self._touchstart(e);
            break;
        case 'touchmove':
            self._touchmove(e);
            break;
        case 'mousemove':
            self._move(e);
            break;
        case 'mouseout':
        case 'mouseup':
        case 'touchend':
            self._end(e);
            break;
    }
};

FLIPBOOK.BookWebGL.prototype.resetCameraPosition = function () {
    this.position.x = this.options.bookX;
    this.position.y = this.options.bookY;
    this.position.z = this.options.bookZ;
};

FLIPBOOK.BookWebGL.prototype._start = function (e) {
    this.mouseDown = true;
	this.onMouseMove = ""
    this.pointX = e.pageX ;
    this.pointY = e.pageY ;
    this.startPoint = e;
    var vector = this._getVector(e);
	vector.unproject(this.camera)
    var raycaster = new THREE.Raycaster( this.camera.position, vector.sub( this.camera.position ).normalize() );
    var intersects = raycaster.intersectObjects( this.pages );
    this.pageMouseDown = (intersects.length > 0);
};

FLIPBOOK.BookWebGL.prototype._touchstart = function (e) {
	if(e.touches.length > 1){
		this.touches =  []
		this.touches[0] = {pageX:e.touches[0].pageX, pageY:e.touches[0].pageY}
		this.touches[1] = {pageX:e.touches[1].pageX, pageY:e.touches[1].pageY}
		var c1 = Math.abs(this.touches[0].pageX - this.touches[1].pageX);
		var c2 = Math.abs(this.touches[0].pageY - this.touches[1].pageY);
		this.touchesDistStart = Math.sqrt(c1 * c1 + c2 * c2);
		return;
	}
	e = e.touches[0]
	this._start(e)
};

FLIPBOOK.BookWebGL.prototype._getVector = function (e) {
    var w = jQuery(this.canvas).width(),
        h = jQuery(this.canvas).height(),
        // x = e.clientX - jQuery(this.canvas).offset().left,
        x = e.pageX - jQuery(this.canvas).offset().left,
        // y = e.clientY - jQuery(this.canvas).offset().top,
        y = e.pageY - jQuery(this.canvas).offset().top,
        cx = jQuery(this.canvas).offset().x,
        cy = jQuery(this.canvas).offset().y
        ;
    return new THREE.Vector3( ( x / w ) * 2 - 1, - ( y / h ) * 2 + 1, 0.5 );
};

FLIPBOOK.BookWebGL.prototype._touchmove = function (e) {
	var self = this;
	if(e.touches.length > 1){
		this.touches =  []
		this.touches[0] = {pageX:e.touches[0].pageX, pageY:e.touches[0].pageY}
		this.touches[1] = {pageX:e.touches[1].pageX, pageY:e.touches[1].pageY}
		var c1 = Math.abs(this.touches[0].pageX - this.touches[1].pageX);
		var c2 = Math.abs(this.touches[0].pageY - this.touches[1].pageY);
		this.touchesDist = Math.sqrt(c1 * c1 + c2 * c2);
		this.zoomTo(this.zoom * Math.pow(this.touchesDist / this.touchesDistStart, .125),0)
		this.updateCameraPosition()
		return;
	}
	e = e.touches[0];
	this._move(e)
};

FLIPBOOK.BookWebGL.prototype._move = function (e) {
    var vector = this._getVector(e);
	vector.unproject(this.camera)
    var raycaster = new THREE.Raycaster( this.camera.position, vector.sub( this.camera.position ).normalize() );
    var intersects = raycaster.intersectObjects( this.pages );

    if(intersects.length > 0){
        this.wrapper.style.cursor = 'pointer';
    }else{
		this.wrapper.style.cursor = 'auto';
	}

    var point =  e,
        deltaX = (point.pageX - this.pointX)*.5,
        deltaY = (point.pageY - this.pointY)*.5;
		
    this.pointX = point.pageX ;
    this.pointY = point.pageY ;

    if(!this.mouseDown){
		this.onMouseMove = "";
        return;
    }
	
	if(intersects.length > 0){
		if(this.onMouseMove == ""){
			if(this.zoom > this.options.zoom)
				this.onMouseMove = "scroll";
		}
	}else{
		if(this.onMouseMove == "")
			this.onMouseMove = "rotate";
	}
	
	if(this.onMouseMove == "scroll"){
		this.position.x += (4* deltaX / this.zoom);
        this.position.y -= (4* deltaY / this.zoom);
	}else if (this.onMouseMove == "rotate"){
		this.tiltTo(deltaY);
        this.panTo(deltaX);
	}
};

FLIPBOOK.BookWebGL.prototype._end = function (e) {
    this.mouseDown = false;
	if(typeof(e.changedTouches) != 'undefined')
		e = e.changedTouches[0]
    this.pointX = e.pageX ;
    this.pointY = e.pageY ;
	this.endPoint = e;
    var vector = this._getVector(this.endPoint);
	vector.unproject(this.camera)
    var raycaster = new THREE.Raycaster( this.camera.position, vector.sub( this.camera.position ).normalize() );
    var intersects = raycaster.intersectObjects( this.pages );
    if(intersects.length > 0){
        if(this.pageMouseDown &&
            Math.abs(this.startPoint.pageX - this.endPoint.pageX) < 5 &&
            Math.abs(this.startPoint.pageY - this.endPoint.pageY) < 5 ){
            var intersect = intersects[0];
            var page = intersect.object;
            if(page.flipping)
                return;
            if(page.isFlippedLeft)
                this.prevPage();
            else
                this.nextPage();
        }
    }
    this.pageMouseDown = false;
};
FLIPBOOK.BookWebGL.prototype.moveCamera = function (deltaX, deltaY) {

};
FLIPBOOK.BookWebGL.prototype.enable = function(){
	
	if(this.enabled) {
		this.onResize();	
		return;
	}
	this.enabled = true;
	
	if(!this.initialized){
		this.init3d();
		this.createPages();
		this.rendering = false;
		// this.disable();
		this.onResize();
	}
	this.render(true);
	this.onResize();
};
FLIPBOOK.BookWebGL.prototype.disable = function(){
	this.enabled = false;
	this.render(false);
};
}

{/* MOD3D */
/**
*
* http://github.com/foo123/MOD3
*
* MOD3 3D Modifier Library (port of actionscript AS3Mod to javascript)
* supports: THREE.js, J3D, Copperlicht, Pre3D
*
* @author Nikos M.
* @url http://nikos-web-development.netai.net/
*
**/
var MOD3=MOD3||{};(function(a){a.Constants={PI:Math.PI,invPI:1/Math.PI,halfPI:0.5*Math.PI,doublePI:2*Math.PI,toRad:1/180*Math.PI,toDeg:1/180*Math.PI};a.ModConstant={LEFT:-1,RIGHT:1,NONE:0,X:1,Y:2,Z:4}})(MOD3);
(function(a){var c=a.Constants;a.XMath={};a.XMath.normalize=function(c,d,e){return d-c==0?1:a.XMath.trim(0,1,(e-c)/d)};a.XMath.toRange=function(a,c,e){return c-a==0?0:a+(c-a)*e};a.XMath.inRange=function(a,c,e,f){typeof f=="undefined"&&(f=!1);return f?e>=a&&e<=c:e>a&&e<c};a.XMath.sign=function(a,c){typeof c=="undefined"&&(c=0);return 0==a?c:a>0?1:-1};a.XMath.trim=function(a,c,e){return Math.min(c,Math.max(a,e))};a.XMath.wrap=function(a,c,e){return e<a?e+(c-a):e>=c?e-(c-a):e};a.XMath.degToRad=function(a){return a*
c.toRad};a.XMath.radToDeg=function(a){return a*c.toDeg};a.XMath.presicion=function(a,c){var e=Math.pow(10,c);return Math.round(a*e)/e};a.XMath.uceil=function(a){return a<0?Math.floor(a):Math.ceil(a)}})(MOD3);
(function(a){a.Range=function(a,b){this.start=0;this.end=1;if(typeof a!="undefined")this.start=a;if(typeof b!="undefined")this.end=b};a.Range.prototype.getSize=function(){return this.end-this.start};a.Range.prototype.move=function(a){this.start+=a;this.end+=a};a.Range.prototype.isIn=function(a){return a>=this.start&&a<=this.end};a.Range.prototype.normalize=function(c){return a.XMath.normalize(this.start,this.end,c)};a.Range.prototype.toRange=function(c){return a.XMath.toRange(this.start,this.end,
c)};a.Range.prototype.trim=function(c){return a.XMath.trim(this.start,this.end,c)};a.Range.prototype.interpolate=function(a,b){return this.toRange(b.normalize(a))};a.Range.prototype.toString=function(){return"["+this.start+" - "+this.end+"]"}})(MOD3);
(function(a){a.Phase=function(a){this.value=0;if(typeof a!="undefined")this.value=a};a.Phase.prototype.getPhasedValue=function(){return Math.sin(this.value)};a.Phase.prototype.getAbsPhasedValue=function(){return Math.abs(this.getPhasedValue())};a.Phase.prototype.getNormValue=function(){return(this.getPhasedValue()+1)*0.5}})(MOD3);
(function(a){a.Point=function(a,b){this.y=this.x=0;if(typeof a!="undefined")this.x=a;if(typeof b!="undefined")this.y=b};a.Point.prototype.clone=function(){return new a.Point(this.x,this.y)}})(MOD3);
(function(a){a.Matrix=function(a,b,d,e){this.m11=1;this.m21=this.m12=0;this.m22=1;if(typeof a!="undefined")this.m11=a;if(typeof b!="undefined")this.m12=b;if(typeof d!="undefined")this.m21=d;if(typeof e!="undefined")this.m22=e};a.Matrix.prototype.rotate=function(a){var b=Math.cos(a),a=Math.sin(a);this.m11=b;this.m12=-a;this.m21=a;this.m22=b;return this};a.Matrix.prototype.scale=function(a,b){this.m21=this.m12=0;if(typeof a!="undefined")this.m22=this.m11=a;if(typeof b!="undefined")this.m22=b;return this};
a.Matrix.prototype.multiply=function(a){var b=this.m11,d=this.m12,e=this.m21,f=this.m22,g=a.m11,h=a.m12,i=a.m21,a=a.m22;this.m11=b*g+d*i;this.m12=b*h+d*a;this.m21=e*g+f*i;this.m22=e*h+f*a;return this};a.Matrix.prototype.transformPoint=function(c){return new a.Point(this.m11*c.x+this.m12*c.y,this.m21*c.x+this.m22*c.y)}})(MOD3);
(function(a){a.Vector3=function(a,b,d){this.z=this.y=this.x=null;this.x=a;this.y=b;this.z=d};a.Vector3.ZERO=function(){return new a.Vector3(0,0,0)};a.Vector3.dot=function(a,b){return a.x*b.x+a.y*b.y+a.z*b.z};a.Vector3.prototype.clone=function(){return new a.Vector3(this.x,this.y,this.z)};a.Vector3.prototype.equals=function(a){return this.x==a.x&&this.y==a.y&&this.z==a.z};a.Vector3.prototype.zero=function(){this.x=this.y=this.z=0};a.Vector3.prototype.negate=function(){return new a.Vector3(-this.x,
-this.y,-this.z)};a.Vector3.prototype.add=function(c){return new a.Vector3(this.x+c.x,this.y+c.y,this.z+c.z)};a.Vector3.prototype.subtract=function(c){return new a.Vector3(this.x-c.x,this.y-c.y,this.z-c.z)};a.Vector3.prototype.multiplyScalar=function(c){return new a.Vector3(this.x*c,this.y*c,this.z*c)};a.Vector3.prototype.multiply=function(c){return new a.Vector3(this.x*c.x,this.y*c.y,this.z*c.z)};a.Vector3.prototype.divide=function(c){c=1/c;return new a.Vector3(this.x*c,this.y*c,this.z*c)};a.Vector3.prototype.normalize=
function(){var a=this.x,b=this.y,d=this.z,a=a*a+b*b+d*d;a>0&&(a=1/Math.sqrt(a),this.x*=a,this.y*=a,this.z*=a)};a.Vector3.prototype.getMagnitude=function(){var a=this.x,b=this.y,d=this.z;return Math.sqrt(a*a+b*b+d*d)};a.Vector3.prototype.setMagnitude=function(a){this.normalize();this.x*=a;this.y*=a;this.z*=a};a.Vector3.prototype.toString=function(){return"["+this.x+" , "+this.y+" , "+this.z+"]"};a.Vector3.prototype.sum=function(a,b){return a.add(b)};a.Vector3.prototype.dot=function(a,b){return a.x*
b.x+a.y*b.y+a.z*b.z};a.Vector3.prototype.cross=function(c,b){var d=c.x,e=c.y,f=c.z,g=b.x,h=b.y,i=b.z;return new a.Vector3(e*i-f*h,f*g-d*i,d*h-e*g)};a.Vector3.prototype.distance=function(a,b){var d=a.x-b.x,e=a.y-b.y,f=a.z-b.z;return Math.sqrt(d*d+e*e+f*f)}})(MOD3);
(function(a){a.Matrix4=function(a,b,d,e,f,g,h,i,n,m,o,k,p,l,j,q){this.n11=1;this.n21=this.n14=this.n13=this.n12=0;this.n22=1;this.n32=this.n31=this.n24=this.n23=0;this.n33=1;this.n43=this.n42=this.n41=this.n34=0;this.n44=1;if(typeof a!="undefined")this.n11=a;if(typeof b!="undefined")this.n12=b;if(typeof d!="undefined")this.n13=d;if(typeof e!="undefined")this.n14=e;if(typeof f!="undefined")this.n21=f;if(typeof g!="undefined")this.n22=g;if(typeof h!="undefined")this.n23=h;if(typeof i!="undefined")this.n24=
i;if(typeof n!="undefined")this.n31=n;if(typeof m!="undefined")this.n32=m;if(typeof o!="undefined")this.n33=o;if(typeof k!="undefined")this.n34=k;if(typeof p!="undefined")this.n41=p;if(typeof l!="undefined")this.n42=l;if(typeof j!="undefined")this.n43=j;if(typeof q!="undefined")this.n44=q};a.Matrix4.prototype.translationMatrix=function(a,b,d){this.n14=a;this.n24=b;this.n34=d;return this};a.Matrix4.prototype.scaleMatrix=function(a,b,d){this.n11=a;this.n22=b;this.n33=d;return this};a.Matrix4.prototype.rotationMatrix=
function(a,b,d,e){var f=Math.cos(e),g=Math.sin(e),e=1-f,h=a*b*e,i=b*d*e,n=a*d*e,m=g*d,o=g*b;g*=a;this.n11=f+a*a*e;this.n12=-m+h;this.n13=o+n;this.n14=0;this.n21=m+h;this.n22=f+b*b*e;this.n23=-g+i;this.n24=0;this.n31=-o+n;this.n32=g+i;this.n33=f+d*d*e;this.n34=0;return this};a.Matrix4.prototype.calculateMultiply=function(a,b){var d=a.n11,e=b.n11,f=a.n21,g=b.n21,h=a.n31,i=b.n31,n=a.n12,m=b.n12,o=a.n22,k=b.n22,p=a.n32,l=b.n32,j=a.n13,q=b.n13,r=a.n23,t=b.n23,s=a.n33,u=b.n33,v=a.n14,w=b.n14,z=a.n24,x=
b.n24,A=a.n34,y=b.n34;this.n11=d*e+n*g+j*i;this.n12=d*m+n*k+j*l;this.n13=d*q+n*t+j*u;this.n14=d*w+n*x+j*y+v;this.n21=f*e+o*g+r*i;this.n22=f*m+o*k+r*l;this.n23=f*q+o*t+r*u;this.n24=f*w+o*x+r*y+z;this.n31=h*e+p*g+s*i;this.n32=h*m+p*k+s*l;this.n33=h*q+p*t+s*u;this.n34=h*w+p*x+s*y+A};a.Matrix4.prototype.multiply=function(a,b){this.calculateMultiply(a,b);return this};a.Matrix4.prototype.multiplyVector=function(a,b){var d=b.x,e=b.y,f=b.z;b.x=d*a.n11+e*a.n12+f*a.n13+a.n14;b.y=d*a.n21+e*a.n22+f*a.n23+a.n24;
b.z=d*a.n31+e*a.n32+f*a.n33+a.n34}})(MOD3);
(function(a){a.VertexProxy=function(a){this.originalZ=this.originalY=this.originalX=this.ratioZ=this.ratioY=this.ratioX=null;if(typeof a!="undefined")this.vertex=a};a.VertexProxy.prototype.setVertex=function(){};a.VertexProxy.prototype.setRatios=function(a,b,d){this.ratioX=a;this.ratioY=b;this.ratioZ=d};a.VertexProxy.prototype.setOriginalPosition=function(a,b,d){this.originalX=a;this.originalY=b;this.originalZ=d};a.VertexProxy.prototype.getX=function(){};a.VertexProxy.prototype.getY=function(){};
a.VertexProxy.prototype.getZ=function(){};a.VertexProxy.prototype.setX=function(){};a.VertexProxy.prototype.setY=function(){};a.VertexProxy.prototype.setZ=function(){};a.VertexProxy.prototype.getValue=function(c){switch(c){case a.ModConstant.X:return this.getX();case a.ModConstant.Y:return this.getY();case a.ModConstant.Z:return this.getZ()}return 0};a.VertexProxy.prototype.setValue=function(c,b){switch(c){case a.ModConstant.X:this.setX(b);break;case a.ModConstant.Y:this.setY(b);break;case a.ModConstant.Z:this.setZ(b)}};
a.VertexProxy.prototype.getRatio=function(c){switch(c){case a.ModConstant.X:return this.ratioX;case a.ModConstant.Y:return this.ratioY;case a.ModConstant.Z:return this.ratioZ}return-1};a.VertexProxy.prototype.getOriginalValue=function(c){switch(c){case a.ModConstant.X:return this.originalX;case a.ModConstant.Y:return this.originalY;case a.ModConstant.Z:return this.originalZ}return 0};a.VertexProxy.prototype.reset=function(){this.setX(this.originalX);this.setY(this.originalY);this.setZ(this.originalZ)};
a.VertexProxy.prototype.collapse=function(){this.originalX=this.getX();this.originalY=this.getY();this.originalZ=this.getZ()};a.VertexProxy.prototype.getVector=function(){return new a.Vector3(this.getX(),this.getY(),this.getZ())};a.VertexProxy.prototype.setVector=function(a){this.setX(a.x);this.setY(a.y);this.setZ(a.z)};a.VertexProxy.prototype.getRatioVector=function(){return new a.Vector3(this.ratioX,this.ratioY,this.ratioZ)}})(MOD3);
(function(a){a.FaceProxy=function(){this.vertices=[]};a.FaceProxy.prototype.addVertex=function(a){this.vertices.push(a)};a.FaceProxy.prototype.getVertices=function(){return this.vertices}})(MOD3);
(function(a){a.MeshProxy=function(){this.depth=this.height=this.width=this.minAxis=this.midAxis=this.maxAxis=this.minZ=this.minY=this.minX=this.maxZ=this.maxY=this.maxX=null;this.vertices=[];this.faces=[];this.mesh=null};a.MeshProxy.prototype.getVertices=function(){return this.vertices};a.MeshProxy.prototype.getFaces=function(){return this.faces};a.MeshProxy.prototype.analyzeGeometry=function(){for(var c=this.getVertices(),b=c.length,d=b,e,f,g,h,i,n,m,o,k,p,l=!0,j=Math.min,q=Math.max;--d>=0;)e=c[d],
f=e.getX(),g=e.getY(),h=e.getZ(),l?(i=n=f,m=o=g,k=p=h,l=!1):(i=j(i,f),m=j(m,g),k=j(k,h),n=q(n,f),o=q(o,g),p=q(p,h)),e.setOriginalPosition(f,g,h);f=n-i;g=o-m;depth=p-k;this.width=f;this.height=g;this.depth=depth;this.minX=i;this.maxX=n;this.minY=m;this.maxY=o;this.minZ=k;this.maxZ=p;d=q(f,q(g,depth));j=j(f,j(g,depth));if(d==f&&j==g)this.minAxis=a.ModConstant.Y,this.midAxis=a.ModConstant.Z,this.maxAxis=a.ModConstant.X;else if(d==f&&j==depth)this.minAxis=a.ModConstant.Z,this.midAxis=a.ModConstant.Y,
this.maxAxis=a.ModConstant.X;else if(d==g&&j==f)this.minAxis=a.ModConstant.X,this.midAxis=a.ModConstant.Z,this.maxAxis=a.ModConstant.Y;else if(d==g&&j==depth)this.minAxis=a.ModConstant.Z,this.midAxis=a.ModConstant.X,this.maxAxis=a.ModConstant.Y;else if(d==depth&&j==f)this.minAxis=a.ModConstant.X,this.midAxis=a.ModConstant.Y,this.maxAxis=a.ModConstant.Z;else if(d==depth&&j==g)this.minAxis=a.ModConstant.Y,this.midAxis=a.ModConstant.X,this.maxAxis=a.ModConstant.Z;for(d=b;--d>=0;)e=c[d],e.setRatios((e.getX()-
i)/f,(e.getY()-m)/g,(e.getZ()-k)/depth)};a.MeshProxy.prototype.resetGeometry=function(){for(var a=this.getVertices(),b=a.length;--b>=0;)a[b].reset()};a.MeshProxy.prototype.collapseGeometry=function(){for(var a=this.getVertices(),b=a.length;--b>=0;)a[b].collapse();this.analyzeGeometry()};a.MeshProxy.prototype.getMin=function(c){switch(c){case a.ModConstant.X:return this.minX;case a.ModConstant.Y:return this.minY;case a.ModConstant.Z:return this.minZ}return-1};a.MeshProxy.prototype.getMax=function(c){switch(c){case a.ModConstant.X:return this.maxX;
case a.ModConstant.Y:return this.maxY;case a.ModConstant.Z:return this.maxZ}return-1};a.MeshProxy.prototype.getSize=function(c){switch(c){case a.ModConstant.X:return this.width;case a.ModConstant.Y:return this.height;case a.ModConstant.Z:return this.depth}return-1};a.MeshProxy.prototype.setMesh=function(a){this.mesh=a;this.vertices=[];this.faces=[]};a.MeshProxy.prototype.postApply=function(){};a.MeshProxy.prototype.updateMeshPosition=function(){}})(MOD3);
(function(a){a.Modifier=function(){this.mod=null};a.Modifier.prototype.setModifiable=function(a){this.mod=a};a.Modifier.prototype.getVertices=function(){return this.mod.getVertices()};a.Modifier.prototype.apply=function(){}})(MOD3);(function(a){a.Library3d=function(){this.id="";this.vertexClass=this.meshClass=null}})(MOD3);(function(a){a.PluginFactory={};a.PluginFactory.getMeshProxy=function(a){return new a.meshClass}})(MOD3);
(function(a){a.ModifierStack=function(c,b){this.lib3d=c;this.stack=this.baseMesh=null;this.baseMesh=a.PluginFactory.getMeshProxy(c);this.baseMesh.setMesh(b);this.baseMesh.analyzeGeometry();this.stack=[]};a.ModifierStack.prototype.addModifier=function(a){a.setModifiable(this.baseMesh);this.stack.push(a)};a.ModifierStack.prototype.apply=function(){this.baseMesh.resetGeometry();for(var a=this.stack,b=a.length,d=0;d<b;)a[d++].apply();this.baseMesh.postApply()};a.ModifierStack.prototype.collapse=function(){this.apply();
this.baseMesh.collapseGeometry();this.stack=[]};a.ModifierStack.prototype.clear=function(){this.stack=[]};a.ModifierStack.prototype.getMeshInfo=function(){return this.baseMesh}})(MOD3);
(function(a){a.Pivot=function(c,b,d){this.pivot=new a.Vector3(c,b,d)};a.Pivot.prototype=new a.Modifier;a.Pivot.prototype.constructor=a.Pivot;a.Pivot.prototype.setMeshCenter=function(){var c=this.mod;this.pivot=new a.Vector3(-(c.minX+0.5*c.width),-(c.minY+0.5*c.height),-(c.minZ+0.5*c.depth))};a.Pivot.prototype.apply=function(){for(var a=this.mod.getVertices(),b=a.length,d=this.pivot,e,f;--b>=0;)e=a[b],f=e.getVector().clone(),e.setVector(f.add(d));this.mod.updateMeshPosition(d.clone().negate())}})(MOD3);
(function(a){a.Bend=function(c,b,d){this.diagAngle=this.angle=this.offset=this.force=null;this.constraint=a.ModConstant.NONE;this.m2=this.m1=this.origin=this.height=this.width=this.mid=this.min=this.max=null;this.switchAxes=!1;this.force=c;this.offset=b;this.setAngle(d)};a.Bend.prototype=new a.Modifier;a.Bend.prototype.constructor=a.Bend;a.Bend.prototype.setAngle=function(c){this.angle=c;this.m1=new a.Matrix;this.m1.rotate(c);this.m2=new a.Matrix;this.m2.rotate(-c)};a.Bend.prototype.setModifiable=
function(c){a.Modifier.prototype.setModifiable.call(this,c);this.max=this.switchAxes?this.mod.midAxis:this.mod.maxAxis;this.min=this.mod.minAxis;this.mid=this.switchAxes?this.mod.maxAxis:this.mod.midAxis;this.width=this.mod.getSize(this.max);this.height=this.mod.getSize(this.mid);this.origin=this.mod.getMin(this.max);this.diagAngle=Math.atan(this.width/this.height)};a.Bend.prototype.apply=function(){if(this.force!=0)for(var c=this.mod.getVertices(),b=c.length,d=this.width,e=this.offset,f=this.origin,
g=this.max,h=this.min,i=this.mid,n=this.m1,m=this.m2,o=f+d*e,k=d/Math.PI/this.force,p=a.Constants.doublePI*(d/(k*a.Constants.doublePI)),l,j,q,r,t=1/d,s=a.Constants.halfPI,u=Math.sin,v=Math.cos;--b>=0;)d=c[b],l=d.getValue(g),j=d.getValue(i),q=d.getValue(h),j=n.transformPoint(new a.Point(l,j)),l=j.x,j=j.y,r=(l-f)*t,this.constraint==a.ModConstant.LEFT&&r<=e||this.constraint==a.ModConstant.RIGHT&&r>=e||(r=s-p*e+p*r,l=u(r)*(k+q),r=v(r)*(k+q),q=l-k,l=o-r),j=m.transformPoint(new a.Point(l,j)),l=j.x,j=j.y,
d.setValue(g,l),d.setValue(i,j),d.setValue(h,q)}})(MOD3);
(function(a){a.Bloat=function(){this.center=a.Vector3.ZERO();this.radius=0;this.a=0.01;this.u=a.Vector3.ZERO()};a.Bloat.prototype=new a.Modifier;a.Bloat.prototype.constructor=a.Bloat;a.Bloat.prototype.setRadius=function(a){this.radius=Math.max(0,a)};a.Bloat.prototype.setA=function(a){this.a=Math.max(0,a)};a.Bloat.prototype.apply=function(){for(var a=this.mod.getVertices(),b=a.length,d=this.center,e=this.radius,f=this.a,g,h;--b>=0;)g=a[b],this.u.x=g.getX()-d.x,this.u.y=g.getY()-d.y,this.u.z=g.getZ()-
d.z,h=this.u.getMagnitude(),this.u.setMagnitude(h+e*Math.exp(-h*f)),g.setX(this.u.x+d.x),g.setY(this.u.y+d.y),g.setZ(this.u.z+d.z)}})(MOD3);
(function(a){a.Twist=function(c){this.vector=new a.Vector3(0,1,0);this.angle=c;this.center=a.Vector3.ZERO()};a.Twist.prototype=new a.Modifier;a.Twist.prototype.constructor=a.Twist;a.Twist.prototype.apply=function(){this.vector.normalize();for(var c=this.mod,b=c.getVertices(),d=b.length,e=this.vector,f=this.angle,g=this.center,c=1/(new a.Vector3(0.5*c.maxX,0.5*c.maxY,0.5*c.maxZ)).getMagnitude()*f,g=-a.Vector3.dot(e,g),h;--d>=0;)f=b[d],h=f.getX()*e.x+f.getY()*e.y+f.getZ()*e.z+g,this.twistPoint(f,h*
c)};a.Twist.prototype.twistPoint=function(c,b){var d=(new a.Matrix4).translationMatrix(c.getX(),c.getY(),c.getZ()),d=(new a.Matrix4).multiply((new a.Matrix4).rotationMatrix(this.vector.x,this.vector.y,this.vector.z,b),d);c.setX(d.n14);c.setY(d.n24);c.setZ(d.n34)}})(MOD3);
(function(a){a.Skew=function(c){this.force=0;this.skewAxis=null;if(typeof c!="undefined")this.force=c;this.offset=0.5;this.constraint=a.ModConstant.NONE;this.falloff=this.power=1;this.swapAxes=this.oneSide=this.inverseFalloff=!1};a.Skew.prototype=new a.Modifier;a.Skew.prototype.constructor=a.Skew;a.Skew.prototype.setModifiable=function(c){a.Modifier.prototype.setModifiable.call(this,c);this.skewAxis=this.skewAxis||this.mod.maxAxis};a.Skew.prototype.apply=function(){for(var c=this.mod.getVertices(),
b=c.length,d=this.constraint,e=this.skewAxis,f=this.offset,g=this.oneSide,h=this.inverseFalloff,i=this.falloff,n=1-i,m=this.power,o=this.force,k=this.getDisplaceAxis(),p,l,j;--b>=0;)p=c[b],!(d==a.ModConstant.LEFT&&p.getRatio(e)<=f)&&!(d==a.ModConstant.RIGHT&&p.getRatio(e)>f)&&(l=p.getRatio(e)-f,g&&(l=Math.abs(l)),j=p.getRatio(k),h&&(j=1-j),j=i+j*n,l=Math.pow(Math.abs(l),m)*a.XMath.sign(l,1),l=p.getValue(k)+o*l*j,p.setValue(k,l))};a.Skew.prototype.getDisplaceAxis=function(){switch(this.skewAxis){case a.ModConstant.X:return this.swapAxes?
a.ModConstant.Z:a.ModConstant.Y;case a.ModConstant.Y:return this.swapAxes?a.ModConstant.Z:a.ModConstant.X;case a.ModConstant.Z:return this.swapAxes?a.ModConstant.Y:a.ModConstant.X;default:return 0}}})(MOD3);
(function(a){a.Taper=function(c){this.power=this.force=null;this.start=0;this.end=1;this.vector=new a.Vector3(1,0,1);this.vector2=new a.Vector3(0,1,0);if(typeof c!="undefined")this.force=c;this.power=1};a.Taper.prototype=new a.Modifier;a.Taper.prototype.constructor=a.Taper;a.Taper.prototype.setFalloff=function(a,b){this.start=0;this.end=1;if(typeof a!="undefined")this.start=a;if(typeof b!="undefined")this.end=b};a.Taper.prototype.apply=function(){for(var c=this.mod.getVertices(),b=c.length,d=this.vector,
e=this.vector2,f=this.force,g=this.power,h,i,n;--b>=0;)h=c[b],i=h.getRatioVector().multiply(e),i=f*Math.pow(i.getMagnitude(),g),i=(new a.Matrix4).scaleMatrix(1+i*d.x,1+i*d.y,1+i*d.z),n=h.getVector(),(new a.Matrix4).multiplyVector(i,n),h.setVector(n)}})(MOD3);
(function(a){a.Wheel=function(){this.radius=this.roll=this.turn=this.speed=null;this.steerVector=new a.Vector3(0,1,0);this.rollVector=new a.Vector3(0,0,1);this.roll=this.turn=this.speed=0};a.Wheel.prototype=new a.Modifier;a.Wheel.prototype.constructor=a.Wheel;a.Wheel.prototype.setModifiable=function(c){a.Modifier.prototype.setModifiable.call(this,c);this.radius=0.5*this.mod.width};a.Wheel.prototype.apply=function(){this.roll+=this.speed;var c=this.mod.getVertices(),b=c.length,d=this.steerVector,e=
this.turn,f=this.rollVector,g=this.roll,h;0!=e?(h=(new a.Matrix4).rotationMatrix(d.x,d.y,d.z,e),d=f.clone(),(new a.Matrix4).multiplyVector(h,d),g=(new a.Matrix4).rotationMatrix(d.x,d.y,d.z,g)):g=(new a.Matrix4).rotationMatrix(f.x,f.y,f.z,g);for(;--b>=0;)d=c[b],f=d.getVector().clone(),0!=e&&(new a.Matrix4).multiplyVector(h,f),(new a.Matrix4).multiplyVector(g,f),d.setX(f.x),d.setY(f.y),d.setZ(f.z)};a.Wheel.prototype.getStep=function(){return this.radius*this.speed*a.Constants.invPI};a.Wheel.prototype.getPerimeter=
function(){return this.radius*a.Constants.doublePI}})(MOD3);
(function(a){a.Break=function(c,b){this.bv=new a.Vector3(0,1,0);this.angle=this.offset=0;if(typeof c!="undefined")this.offset=c;if(typeof b!="undefined")this.angle=b;this.range=new a.Range(0,1)};a.Break.prototype=new a.Modifier;a.Break.prototype.constructor=a.Break;a.Break.prototype.apply=function(){var c=this.mod,b=c.getVertices(),d=b.length,e=this.range,f=this.angle,g=this.bv,h,i,c=new a.Vector3(0,0,-(c.minZ+c.depth*this.offset));h=c.negate();for(i=(new a.Matrix4).rotationMatrix(g.x,g.y,g.z,f);--d>=
0;)f=b[d],g=f.getVector(),g=g.add(c),g.z>=0&&e.isIn(f.ratioY)&&(new a.Matrix4).multiplyVector(i,g),g=g.add(h),f.setX(g.x),f.setY(g.y),f.setZ(g.z)}})(MOD3);
(function(a){a.Noise=function(c){this.force=0;this.axc=a.ModConstant.NONE;this.end=this.start=0;if(typeof c!="undefined")this.force=c};a.Noise.prototype=new a.Modifier;a.Noise.prototype.constructor=a.Noise;a.Noise.prototype.constraintAxes=function(a){this.axc=a};a.Noise.prototype.setFalloff=function(a,b){this.start=0;this.end=1;if(typeof a!="undefined")this.start=a;if(typeof b!="undefined")this.end=b};a.Noise.prototype.apply=function(){for(var a=this.mod,b=this.axc,d=this.start,e=this.end,f=a.getVertices(),
g=f.length,h=this.force,i=0.5*h,n=Math.random,m,o,k;--g>=0;)m=f[g],o=n()*h-i,k=m.getRatio(a.maxAxis),d<e?(k<d&&(k=0),k>e&&(k=1)):d>e?(k=1-k,k>d&&(k=0),k<e&&(k=1)):k=1,b&1||m.setX(m.getX()+o*k),b>>1&1||m.setY(m.getY()+o*k),b>>2&1||m.setZ(m.getZ()+o*k)}})(MOD3);(function(a){a.LibraryThree=function(){this.id="Three.js";this.meshClass=a.MeshThree;this.vertexClass=a.VertexThree};a.LibraryThree.prototype=new a.Library3d;a.LibraryThree.prototype.constructor=a.LibraryThree})(MOD3);
(function(a){a.VertexThree=function(a){this.mesh=a};a.VertexThree.prototype=new a.VertexProxy;a.VertexThree.prototype.setVertex=function(a){this.vertex=a;this.originalX=a.x;this.originalY=a.y;this.originalZ=a.z};a.VertexThree.prototype.getX=function(){return this.vertex.x};a.VertexThree.prototype.getY=function(){return this.vertex.y};a.VertexThree.prototype.getZ=function(){return this.vertex.z};a.VertexThree.prototype.setX=function(a){this.vertex.x=a;a=this.mesh;a.geometry.verticesNeedUpdate=!0;a.geometry.normalsNeedUpdate=
!0;a.geometry.buffersNeedUpdate=!0;a.geometry.dynamic=!0};a.VertexThree.prototype.setY=function(a){this.vertex.y=a;a=this.mesh;a.geometry.verticesNeedUpdate=!0;a.geometry.normalsNeedUpdate=!0;a.geometry.buffersNeedUpdate=!0;a.geometry.dynamic=!0};a.VertexThree.prototype.setZ=function(a){this.vertex.z=a;a=this.mesh;a.geometry.verticesNeedUpdate=!0;a.geometry.normalsNeedUpdate=!0;a.geometry.buffersNeedUpdate=!0;a.geometry.dynamic=!0}})(MOD3);
(function(a){a.MeshThree=function(){};a.MeshThree.prototype=new a.MeshProxy;a.MeshThree.prototype.setMesh=function(c){a.MeshProxy.prototype.setMesh.call(this,c);for(var c=[],b=0,d=this.mesh.geometry.vertices,e=d.length,f=this.mesh.geometry.faces,g=f.length,h,b=0;b<e;)h=new a.VertexThree(this.mesh),h.setVertex(d[b]),this.vertices.push(h),c[d[b]]=h,b++;for(b=0;b<g;)e=new a.FaceProxy,f[b]instanceof THREE.Face3?(e.addVertex(c[d[f[b].a]]),e.addVertex(c[d[f[b].b]]),e.addVertex(c[d[f[b].c]])):f[b]instanceof
THREE.Face4&&(e.addVertex(c[d[f[b].a]]),e.addVertex(c[d[f[b].b]]),e.addVertex(c[d[f[b].c]]),e.addVertex(c[d[f[b].d]])),this.faces.push(e),b++;delete lookup};a.MeshThree.prototype.updateMeshPosition=function(a){var b=this.mesh;b.position.x+=a.x;b.position.y+=a.y;b.position.z+=a.z}})(MOD3);(function(a){a.LibraryPre3D=function(){this.id="pre3d.js";this.meshClass=a.MeshPre3D;this.vertexClass=a.VertexPre3D};a.LibraryThree.prototype=new a.Library3d;a.LibraryThree.prototype.constructor=a.LibraryPre3D})(MOD3);
(function(a){a.VertexPre3D=function(){};a.VertexPre3D.prototype=new a.VertexProxy;a.VertexPre3D.prototype.setVertex=function(a){this.vertex=a;this.originalX=a.x;this.originalY=a.y;this.originalZ=a.z};a.VertexPre3D.prototype.getX=function(){return this.vertex.x};a.VertexPre3D.prototype.getY=function(){return this.vertex.y};a.VertexPre3D.prototype.getZ=function(){return this.vertex.z};a.VertexPre3D.prototype.setX=function(a){this.vertex.x=a};a.VertexPre3D.prototype.setY=function(a){this.vertex.y=a};
a.VertexPre3D.prototype.setZ=function(a){this.vertex.z=a}})(MOD3);
(function(a){a.MeshPre3D=function(){};a.MeshPre3D.prototype=new a.MeshProxy;a.MeshPre3D.prototype.setMesh=function(c){a.MeshProxy.prototype.setMesh.call(this,c);for(var c=[],b=this.mesh.vertices,d=this.mesh.quads,e=b.length,f=d.length,g=0;g<e;g++){var h=new a.VertexPre3D;h.setVertex(b[g]);this.vertices.push(h);c[b[g]]=h}for(g=0;g<f;g++)e=new a.FaceProxy,d[g]instanceof Pre3d.QuadFace&&(e.addVertex(c[b[d[g].i0]]),e.addVertex(c[b[d[g].i1]]),e.addVertex(c[b[d[g].i2]]),d[g].i3!=null&&e.addVertex(c[b[d[g].i3]])),
this.faces.push(e);delete lookup};a.MeshPre3D.prototype.updateMeshPosition=function(){}})(MOD3);(function(a){a.LibraryJ3D=function(){this.id="J3D";this.meshClass=a.MeshJ3D;this.vertexClass=a.VertexJ3D};a.LibraryJ3D.prototype=new a.Library3d;a.LibraryJ3D.prototype.constructor=a.LibraryJ3D})(MOD3);
(function(a){a.VertexJ3D=function(a){this.geometry=a};a.VertexJ3D.prototype=new a.VertexProxy;a.VertexJ3D.prototype.setVertex=function(a){this.vertex=a;var b=this.geometry;this.originalX=b.vertexPositionBuffer.data[a];this.originalY=b.vertexPositionBuffer.data[a+1];this.originalZ=b.vertexPositionBuffer.data[a+2]};a.VertexJ3D.prototype.getX=function(){return this.geometry.vertexPositionBuffer.data[this.vertex]};a.VertexJ3D.prototype.getY=function(){return this.geometry.vertexPositionBuffer.data[this.vertex+
1]};a.VertexJ3D.prototype.getZ=function(){return this.geometry.vertexPositionBuffer.data[this.vertex+2]};a.VertexJ3D.prototype.setX=function(a){var b=this.geometry;b.vertexPositionBuffer.data[this.vertex]=a;b.replaceArray(b.vertexPositionBuffer,b.vertexPositionBuffer.data)};a.VertexJ3D.prototype.setY=function(a){var b=this.geometry;b.vertexPositionBuffer.data[this.vertex+1]=a;b.replaceArray(b.vertexPositionBuffer,b.vertexPositionBuffer.data)};a.VertexJ3D.prototype.setZ=function(a){var b=this.geometry;
b.vertexPositionBuffer.data[this.vertex+2]=a;b.replaceArray(b.vertexPositionBuffer,b.vertexPositionBuffer.data)}})(MOD3);
(function(a){a.MeshJ3D=function(){};a.MeshJ3D.prototype=new a.MeshProxy;a.MeshJ3D.prototype.setMesh=function(c){a.MeshProxy.prototype.setMesh.call(this,c);for(var b=0,d=c.geometry.vertexPositionBuffer.data.length,e=c.geometry.vertexPositionBuffer.itemSize,f,b=0;b<d;)f=new a.VertexJ3D(c.geometry),f.setVertex(b),this.vertices.push(f),b+=e;this.faces=null};a.MeshJ3D.prototype.updateMeshPosition=function(a){var b=this.mesh;b.position.x+=a.x;b.position.y+=a.y;b.position.z+=a.z}})(MOD3);
(function(a){a.LibraryCopperlicht=function(){this.id="Copperlicht";this.meshClass=a.MeshCopperlicht;this.vertexClass=a.VertexCopperlicht};a.LibraryCopperlicht.prototype=new a.Library3d;a.LibraryCopperlicht.prototype.constructor=a.LibraryCopperlicht})(MOD3);
(function(a){a.VertexCopperlicht=function(a,b){this.node=a;this.buffer=b};a.VertexCopperlicht.prototype=new a.VertexProxy;a.VertexCopperlicht.prototype.setVertex=function(a){this.vertex=a;this.originalX=this.vertex.Pos.X;this.originalY=this.vertex.Pos.Y;this.originalZ=this.vertex.Pos.Z};a.VertexCopperlicht.prototype.getX=function(){return this.vertex.Pos.X};a.VertexCopperlicht.prototype.getY=function(){return this.vertex.Pos.Y};a.VertexCopperlicht.prototype.getZ=function(){return this.vertex.Pos.Z};
a.VertexCopperlicht.prototype.setX=function(a){this.vertex.Pos.X=a;this.buffer.update(!0)};a.VertexCopperlicht.prototype.setY=function(a){this.vertex.Pos.Y=a;this.buffer.update(!0)};a.VertexCopperlicht.prototype.setZ=function(a){this.vertex.Pos.Z=a;this.buffer.update(!0)}})(MOD3);
(function(a){a.MeshCopperlicht=function(){};a.MeshCopperlicht.prototype=new a.MeshProxy;a.MeshCopperlicht.prototype.setMesh=function(c){a.MeshProxy.prototype.setMesh.call(this,c);for(var c=this.mesh.getMesh().GetMeshBuffers(),b=[],d=0;d<c.length;d++)for(var b=c[d].Vertices,e=b.length,f=0;f<e;f++){var g=new a.VertexCopperlicht(this.mesh,c[d]);g.setVertex(b[f]);this.vertices.push(g)}this.faces=null;delete lookup};a.MeshCopperlicht.prototype.updateMeshPosition=function(a){this.mesh.Pos.X+=a.x;this.mesh.Pos.Y+=
a.y;this.mesh.Pos.Z+=a.z}})(MOD3);
}

