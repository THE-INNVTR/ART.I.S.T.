<html>
<head>
<?php 
if($_SERVER["REQUEST_METHOD"] == "POST"){
	//will include code that uploads the canvas as an image to the appropriate folder
	//and will save information about that image in a database
}
?>
<title>
LEARNING...
</title>
</head>
<body>

<p>Image</p>
<img id="image" src="square.jpg" alt="SQL" height="200px" width="200px"/>

<p>Canvas</p>
<canvas id="myCanvas" height="200px" width="200px" onclick="scan()"></canvas>

<script>
/*Learning AI
	task: identify objects within images && create original images
	
	* step 1: distinguish between different colors and shades
	* step 2: identify border areas between objects 
	* step 3: identify different objects
	X step 4: create a categorized catalog of objects
	X step 5: recreate coherent composites of those objects
*/
var c;
var ctx;
var img;
var imgData;

document.getElementById("image").onload = function() {	
	//Create canvas object
    c = document.getElementById("myCanvas");
    ctx = c.getContext("2d");
    img = document.getElementById("image");
    ctx.drawImage(img, 0, 0, img.width, img.height);
};	

var objects;
function scan(){
    imgData = ctx.getImageData(0,0,img.width,img.height);//getImageData holds info for every pixel within a specified area on the canvas
	objects = [];
	objects[0] = [];

	for(i = 0; i < imgData.data.length; i += 4){
		//receive x and y coords of pixel 
		var x = Math.round((i / 4) % img.width);
		var y = Math.round((i / 4) / img.width);
		var r = i;
		var g = i + 1;
		var b = i + 2;
		var a = i + 3;
		var color = getColor(r,g,b);
		if(i == 0){
			objects[0].push(i);
		}

		//Coords + RGB of pixel to the right
		var Rx = x + 1;
		var Ry = y;
		var Rr = i + 4;
		var Rg = i + 5;
		var Rb = i + 6;
		var Ra = i + 7;
		var Rcolor = getColor(Rr,Rg,Rb);
		
		//Coords + RGB of pixel to the left
		var Lx = x - 1;
		var Ly = y;
		var Lr = i - 4;
		var Lg = i - 3;
		var Lb = i - 2;
		var La = i - 1;
		var Lcolor = getColor(Lr,Lg,Lb);
		
		//Coords + RGB of pixel above
		var Tx = x;
		var Ty = y + 1;
		var Tr = i - (img.width*4);
		var Tg = Tr + 1;
		var Tb = Tr + 2;
		var Ta = Tr + 3;
		var Tcolor = getColor(Tr,Tg,Tb);
		
		//Coords + RGB of pixel below
		var Bx = x;
		var By = y - 1;
		var Br = i + (img.width*4);
		var Bg = Br + 1;
		var Bb = Br + 2;
		var Ba = Br + 3;
		var Bcolor = getColor(Br,Bg,Bb);
		
		//Coords + RGB of pixel up and to the right
		var TRx = x + 1;
		var TRy = y + 1;
		var TRr = Tr + 4;
		var TRg = Tr + 5;
		var TRb = Tr + 6;
		var TRa = Tr + 7;
		var TRcolor = getColor(TRr,TRg,TRb);
		
		//Coords + RGB of pixel up and to the left
		var TLx = x - 1;
		var TLy = y + 1;
		var TLr = Tr - 4;
		var TLg = Tr - 3;
		var TLb = Tr - 2;
		var TLa = Tr - 1;
		var TLcolor = getColor(TLr,TLg,TLb);
		
		//Coords + RGB of pixel down and to the right
		var BRx = x + 1;
		var BRy = y - 1;
		var BRr = Br + 4;
		var BRg = Br + 5;
		var BRb = Br + 6;
		var BRa = Br + 7;
		var BRcolor = getColor(BRr,BRg,BRb);
		
		//Coords + RGB of pixel down and to the left
		var BLx = x - 1;
		var BLy = y - 1;
		var BLr = Br - 4;
		var BLg = Br - 3;
		var BLb = Br - 2;
		var BLa = Br - 1;
		var BLcolor = getColor(BLr,BLg,BLb);
		
		
		//Identifying Objects
		if(Lr >= 0 && Lr < imgData.data.length && Lx >= 0 && Lx < img.width && Ly >= 0 && Ly < img.height 
		|| Tr >= 0 && Tr < imgData.data.length && Tx >= 0 && Tx < img.width && Ty >= 0 && Ty < img.height
		|| TRr >= 0 && TRr < imgData.data.length && TRx >= 0 && TRx < img.width && TRy >= 0 && TRy < img.height
		|| TLr >= 0 && TLr < imgData.data.length && TLx >= 0 && TLx < img.width && TLy >= 0 && TLy < img.height){
			if(Lcolor == color){//if color of left pixel is same as center pixel and if left pixel is part of an object then add center pixel to same object
				for(j = 0; j < objects.length; j++){
					for(k = 0; k < objects[j].length; k++){
						if(Lr == objects[j][k]){
							objects[j].push(i);
						}
					}
				}
			}
			else if(Tcolor == color){//if color of above pixel is same as center pixel and if above pixel is part of an object then add center pixel to same object
				for(j = 0; j < objects.length; j++){
					for(k = 0; k < objects[j].length; k++){
						if(Tr == objects[j][k]){
							objects[j].push(i);
						}
					}
				}
			}
			else if(TRcolor == color){//if color of top-right pixel is same as center pixel and if above pixel is part of an object then add center pixel to same object
				for(j = 0; j < objects.length; j++){
					for(k = 0; k < objects[j].length; k++){
						if(TRr == objects[j][k]){
							objects[j].push(i);
						}
					}
				}
			}
			else if(TLcolor == color){//if color of above pixel is same as center pixel and if above pixel is part of an object then add center pixel to same object
				for(j = 0; j < objects.length; j++){
					for(k = 0; k < objects[j].length; k++){
						if(TLr == objects[j][k]){
							objects[j].push(i);
						}
					}
				}
			}
			else if(Lcolor != color
				 && Tcolor != color
				 && TRcolor != color
				 && TLcolor != color){//if color of left pixel is not same as center pixel then add center pixel to a new object
				objects[objects.length] = [];
				objects[objects.length - 1].push(i);
			}
		}
	}
	
	/*
	alert(objects.length);
	alert(objects[0].length);
	alert(objects[1].length);
	//color codes object one as black
	for(l = 0; l < objects[0].length; l++){
		imgData.data[objects[0][l]] = 0;
		imgData.data[objects[0][l] + 1] = 0;
		imgData.data[objects[0][l] + 2] = 0;
	}
	//color codes object two as red
	for(l = 0; l < objects[1].length; l++){
		imgData.data[objects[1][l]] = 255;
		imgData.data[objects[1][l] + 1] = 0;
		imgData.data[objects[1][l] + 2] = 0;
	}
	ctx.putImageData(imgData, 0, 0);
	*/
	border();
}

//Identifying Borders Between Objects
var borderPix;
function border(){
	borderPix = [];
	for(n = 0; n < objects.length; n++){
		borderPix[n] = [];
		for(o = 0; o < objects[n].length; o++){
			
			var x = Math.round((0 / 4) % img.width);
			var y = Math.round((0 / 4) / img.width);
			var r =  objects[n][o];
			var g = objects[n][o] + 1;
			var b = objects[n][o] + 2;
			var a = objects[n][o] + 3;
			var color = getColor(r,g,b);

			//Coords + RGB of pixel to the right
			var Rx = x + 1;
			var Ry = y;
			var Rr = objects[n][o] + 4;
			var Rg = objects[n][o] + 5;
			var Rb = objects[n][o] + 6;
			var Ra = objects[n][o] + 7;
			var Rcolor = getColor(Rr,Rg,Rb);
			
			//Coords + RGB of pixel to the left
			var Lx = x - 1;
			var Ly = y;
			var Lr = objects[n][o] - 4;
			var Lg = objects[n][o] - 3;
			var Lb = objects[n][o] - 2;
			var La = objects[n][o] - 1;
			var Lcolor = getColor(Lr,Lg,Lb);
			
			//Coords + RGB of pixel above
			var Tx = x;
			var Ty = y + 1;
			var Tr = objects[n][o] - (img.width*4);
			var Tg = Tr + 1;
			var Tb = Tr + 2;
			var Ta = Tr + 3;
			var Tcolor = getColor(Tr,Tg,Tb);
			
			//Coords + RGB of pixel below
			var Bx = x;
			var By = y - 1;
			var Br = objects[n][o] + (img.width*4);
			var Bg = Br + 1;
			var Bb = Br + 2;
			var Ba = Br + 3;
			var Bcolor = getColor(Br,Bg,Bb);
			
			//Coords + RGB of pixel up and to the right
			var TRx = x + 1;
			var TRy = y + 1;
			var TRr = Tr + 4;
			var TRg = Tr + 5;
			var TRb = Tr + 6;
			var TRa = Tr + 7;
			var TRcolor = getColor(TRr,TRg,TRb);
			
			//Coords + RGB of pixel up and to the left
			var TLx = x - 1;
			var TLy = y + 1;
			var TLr = Tr - 4;
			var TLg = Tr - 3;
			var TLb = Tr - 2;
			var TLa = Tr - 1;
			var TLcolor = getColor(TLr,TLg,TLb);
			
			//Coords + RGB of pixel down and to the right
			var BRx = x + 1;
			var BRy = y - 1;
			var BRr = Br + 4;
			var BRg = Br + 5;
			var BRb = Br + 6;
			var BRa = Br + 7;
			var BRcolor = getColor(BRr,BRg,BRb);
			
			//Coords + RGB of pixel down and to the left
			var BLx = x - 1;
			var BLy = y - 1;
			var BLr = Br - 4;
			var BLg = Br - 3;
			var BLb = Br - 2;
			var BLa = Br - 1;
			var BLcolor = getColor(BLr,BLg,BLb);
			
			if(Rr >= 0 && Rr < imgData.data.length && Rx >= 0 && Rx < img.width && Ry >= 0 && Ry < img.height){//Makes sure pixel to the right exists in the correct position
				if(Rcolor != color){//Checks if color of right pixel is same as center pixel
					borderPix[n].push(objects[n][o]);//If not, adds center pixel to border pixel array
				}
			}
			if(Lr >= 0 && Lr < imgData.data.length && Lx >= 0 && Lx < img.width && Ly >= 0 && Ly < img.height){
				if(Lcolor != color){
					borderPix[n].push(objects[n][o]);
				}
			}
			if(Tr >= 0 && Tr < imgData.data.length && Tx >= 0 && Tx < img.width && Ty >= 0 && Ty < img.height){
				if(Tcolor != color){
					borderPix[n].push(objects[n][o]);
				}
			}
			if(Br >= 0 && Br < imgData.data.length && Bx >= 0 && Bx < img.width && By >= 0 && By < img.height){
				if(Bcolor != color){
					borderPix[n].push(objects[n][o]);
				}
			}
			if(TRr >= 0 && TRr < imgData.data.length && TRx >= 0 && TRx < img.width && TRy >= 0 && TRy < img.height){
				if(TRcolor != color){
					borderPix[n].push(objects[n][o]);
				}
			}
			if(TLr >= 0 && TLr < imgData.data.length && TLx >= 0 && TLx < img.width && TLy >= 0 && TLy < img.height){
				if(TLcolor != color){
					borderPix[n].push(objects[n][o]);
				}
			}
			if(BRr >= 0 && BRr < imgData.data.length && BRx >= 0 && BRx < img.width && BRy >= 0 && BRy < img.height){
				if(BRcolor != color){
					borderPix[n].push(objects[n][o]);
				}
			}
			if(BLr >= 0 && BLr < imgData.data.length && BLx >= 0 && BLx < img.width && BLy >= 0 && BLy < img.height){
				if(BLcolor != color){
					borderPix[n].push(objects[n][o]);
				}
			}
		}
	}
	/*
	alert(borderPix.length);
	//Color-codes all border pixels
	for(p = 0; p < borderPix.length; p++){
		for(q = 0; q < borderPix[p].length; q++){
			imgData.data[borderPix[p][q]] = 0;
			imgData.data[borderPix[p][q] + 1] = 0;
			imgData.data[borderPix[p][q] + 2] = 0;
		}
	}
	ctx.putImageData(imgData, 0, 0);
	*/
}

function getColor(R,G,B){
	var r = imgData.data[R];
	var g = imgData.data[G];
	var b = imgData.data[B];
	
	if(r > 240 && g > 240 && b > 240){
		color = "white";
	}
	else if(r < 15 && g < 15 && b < 15){
		color = "black";
	}
	else if(r > g && r > b){
		if(g > b){
			return "orange";
		}
		else if(b > g){
			return "pink";
		}
		else if(g == b){
			return "red";
		}
	}
	else if(g > r && g > b){
		if(r > b){
			return "yellow-green";
		}
		else if(b > r){
			return "blue-green";
		}
		else if(r == b){
			return "green";
		}
	}
	else if(b > r && b > g){
		if(r > g){
			return "violet";
		}
		else if(g > r){
			return "cerulean";
		}
		else if(r == g){
			return "blue";
		}
	}
	else if(r == g && r > b){
		return "yellow";
	}
	else if(g == b && g > r){
		return "cyan";
	}
	else if(b == r && b > g){
		return "magenta";
	}
	else if(r == g && r == b){
		if(r > 230){
			return "white";
		}
		else if(r < 25){
			return "black";
		}
		else return "gray";
	}
}

/* Not tested

//Makes entire image white
function reset(){
	for(l = 0; l < objects.length; l++){
		for(m = 0; m < objects[l].length; m++){
			imgData.data[objects[l][m]] = 255;
			imgData.data[objects[l][m] + 1] = 255;
			imgData.data[objects[l][m] + 2] = 255;
		}
	}
	ctx.putImageData(imgData, 0, 0);
}

//Saves each object within canvas as an image
function save(){
	var form = document.createElement("FORM");
	form.method = "post";
	form.action = "Learning.php";
	var input;
	var data;
	reset();
	
	for(l = 0; l < objects.length; l++){
		for(m = 0; m < objects[l].length; m++){//Makes current object black
			imgData.data[objects[l][m]] = 0;
			imgData.data[objects[l][m] + 1] = 0;
			imgData.data[objects[l][m] + 2] = 0;
		}
		ctx.putImageData(imgData, 0, 0);
		data = ctx.toDataURL();//Converts canvas to image data
		
		
		input = document.createElement("INPUT");
		input.id = l;
		input.value = data;
		form.appendChild(input);
		
		reset();
	}
	form.submit();
}
*/
</script>
</body>
</html>