let tiles = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
let blankLocation = 15;
let solved = false;
let shuffling = true;
let hash = "03b369a1fdc494466050ec5a58900286bfe7ecf918fda82b1ee02ce0abd0623c";
let hash2 = "b9a975ec43eb633014d6657a4f576cd266480939ec10c8585eca08c1342fa922";

function shuffle()
{
    // have to manually set these here to prevent them from just saying "Default" when I try to access them in the DOM
    document.getElementById("tile0").style.backgroundPosition = "0px -0px";
    document.getElementById("tile1").style.backgroundPosition = "-90px -0px";
    document.getElementById("tile2").style.backgroundPosition = "-180px -0px";
    document.getElementById("tile3").style.backgroundPosition = "-270px -0px";
    document.getElementById("tile4").style.backgroundPosition = "0px -90px";
    document.getElementById("tile5").style.backgroundPosition = "-90px -90px";
    document.getElementById("tile6").style.backgroundPosition = "-180px -90px";
    document.getElementById("tile7").style.backgroundPosition = "-270px -90px";
    document.getElementById("tile8").style.backgroundPosition = "0px -180px";
    document.getElementById("tile9").style.backgroundPosition = "-90px -180px";
    document.getElementById("tile10").style.backgroundPosition = "-180px -180px";
    document.getElementById("tile11").style.backgroundPosition = "-270px -180px";
    document.getElementById("tile12").style.backgroundPosition = "0px -270px";
    document.getElementById("tile13").style.backgroundPosition = "-90px -270px";
    document.getElementById("tile14").style.backgroundPosition = "-180px -270px";

    for(let i = 0; i < 10000; i++)
    {
        clickTile(Math.floor(Math.random() * 16));
    }
    shuffling = false;
}

function swapWithBlank(otherLocation)
{
    let blankTile = document.getElementById("tile" + blankLocation);
    let otherTile = document.getElementById("tile" + otherLocation);
    tiles[blankLocation] = tiles[otherLocation];
    tiles[otherLocation] = 15;
    blankLocation = otherLocation;
    
    blankTile.style.background = "url(./images/slider.png)";
    blankTile.style.backgroundPosition = otherTile.style.backgroundPosition;
    blankTile.style.cursor = "pointer";
    otherTile.style.background = "white";
    otherTile.style.cursor = "default";
}

function canSwap(tileLocation)
{
    let tileRow = Math.floor(tileLocation / 4);
    let tileCol = tileLocation % 4;
    let blankRow = Math.floor(blankLocation / 4);
    let blankCol = blankLocation % 4;
    return((tileRow == blankRow && tileCol + 1 == blankCol) || (tileRow == blankRow && tileCol - 1 == blankCol) || (tileRow + 1 == blankRow && tileCol == blankCol) || (tileRow - 1 == blankRow && tileCol == blankCol));
}

function clickTile(tileLocation)
{
    if(!solved && canSwap(tileLocation))
    {
        swapWithBlank(tileLocation);
        if(!shuffling && isSolved())
        {
            solved = true;
            document.getElementById("tile15").style.background = "url(./images/slider.png)";
            document.getElementById("tile15").style.backgroundPosition = "-270px -270px";
            for(let i = 0; i < 15; i++)
            {
                document.getElementById("tile" + i).style.cursor = "default";
            }
        }
    }
}

function isSolved()
{
    if(solved)
        return true;
    for(let i = 0; i < 16; i++)
    {
        if(tiles[i] != i)
            return false;
    }
    return true;
}

document.querySelector("#submit-answer").onclick = function()
{
    event.preventDefault();

    let answer = document.getElementById("answer").value;
    answer = answer.toLowerCase();
    if(SHA256(answer) == hash || SHA256(answer) == hash2)
    {
        document.getElementById("output").innerHTML = "Correct!";
        let user_id = document.querySelector("#user-id-hidden").value;
        let puzzle_id = document.querySelector("#puzzle-id-hidden").value;
        ajaxPost("completePuzzle.php", "user_id=" + user_id + "&puzzle_id=" + puzzle_id, function(results){});
    }
    else
    {
        document.getElementById("output").innerHTML = "Incorrect. Try again.";
    }
}

let hints = document.querySelectorAll("#hint-section .h5");

for(let i = 0; i < hints.length; i++)
{
    hints[i].onclick = function()
    {	
        if(this.nextElementSibling.classList.contains("hide"))
        {
            this.nextElementSibling.classList.remove("hide");
            this.innerHTML = this.innerHTML.substring(0, this.innerHTML.length - 1) + "▲";
        }
        else
        {
            this.nextElementSibling.classList.add("hide");
            this.innerHTML = this.innerHTML.substring(0, this.innerHTML.length - 1) + "▼";
        }
    }
}

function ajaxPost(endpointUrl, postData, returnFunction)
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', endpointUrl, true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function(){
        if(xhr.readyState == XMLHttpRequest.DONE)
        {
            if(xhr.status == 200)
            {
				returnFunction(xhr.responseText);
            }
            else
            {
				alert('AJAX Error.');
				console.log(xhr.status);
			}
		}
    }
	xhr.send(postData);
}

function postComment()
{
    event.preventDefault();
    let user_id = document.querySelector("#user-id-hidden").value;
    let puzzle_id = document.querySelector("#puzzle-id-hidden").value;
    let comment = document.querySelector("#comment").value;
	ajaxPost("addComment.php", "user_id=" + user_id + "&puzzle_id=" + puzzle_id + "&comment=" + comment, function(results)
	{
        let noComments = document.querySelector("#no-comments");
        noComments.innerHTML = "";

        let comment = document.querySelector("#comment").value.trim();

        let newComment = document.createElement("div");
        newComment.classList.add("comment");
        newComment.innerHTML = "<strong>" + document.querySelector("#username-hidden").value + "</strong> says: " + comment;

        let parent = document.querySelector("#comment-section");
        parent.insertBefore(newComment, parent.firstChild.nextSibling.nextSibling);
	});
}

function showForm(comment_id)
{
    document.querySelector("#edit-form-" + comment_id).style.display = 'initial';
}

function hideForm(comment_id)
{
    document.querySelector("#edit-form-" + comment_id).style.display = 'none';
}

function editComment(comment_id)
{
    event.preventDefault();
    let comment = document.querySelector("#edit-" + comment_id).value;
    if(comment.trim() == "")
        document.querySelector("#error-" + comment_id).innerHTML = "Comment cannot be blank";
    else
    {
        ajaxPost("editComment.php", "comment_id=" + comment_id + "&comment=" + comment, function(results)
        {
            document.querySelector("#comment-text-" + comment_id).innerHTML = comment;
            document.querySelector("#error-" + comment_id).innerHTML += results;
        });
        hideForm(comment_id);
        document.querySelector("#error-" + comment_id).innerHTML = "";
    }
}

function deleteComment(comment_id)
{
    event.preventDefault();
    ajaxPost("deleteComment.php", "comment_id=" + comment_id, function(results)
    {
        document.querySelector("#comment-" + comment_id).remove();
        document.querySelector("#comment-text-" + comment_id).remove();
    });
    hideForm(comment_id);
    document.querySelector("#edit-form-" + comment_id).style.display = 'none';
    document.querySelector("#error-" + comment_id).innerHTML = "";
}

// the code below is copied from the internet (obviously)
function SHA256(s){ 
    let chrsz  = 8; 
    let hexcase = 0; 
    function safe_add (x, y) { 
        let lsw = (x & 0xFFFF) + (y & 0xFFFF); 
        let msw = (x >> 16) + (y >> 16) + (lsw >> 16); 
        return (msw << 16) | (lsw & 0xFFFF); 
    } 
    function S (X, n) { return ( X >>> n ) | (X << (32 - n)); } 
    function R (X, n) { return ( X >>> n ); } 
    function Ch(x, y, z) { return ((x & y) ^ ((~x) & z)); } 
    function Maj(x, y, z) { return ((x & y) ^ (x & z) ^ (y & z)); } 
    function Sigma0256(x) { return (S(x, 2) ^ S(x, 13) ^ S(x, 22)); } 
    function Sigma1256(x) { return (S(x, 6) ^ S(x, 11) ^ S(x, 25)); } 
    function Gamma0256(x) { return (S(x, 7) ^ S(x, 18) ^ R(x, 3)); } 
    function Gamma1256(x) { return (S(x, 17) ^ S(x, 19) ^ R(x, 10)); } 
    function core_sha256 (m, l) { 
        let K = new Array(0x428A2F98, 0x71374491, 0xB5C0FBCF, 0xE9B5DBA5, 0x3956C25B, 0x59F111F1, 0x923F82A4, 0xAB1C5ED5, 0xD807AA98, 0x12835B01, 0x243185BE, 0x550C7DC3, 0x72BE5D74, 0x80DEB1FE, 0x9BDC06A7, 0xC19BF174, 0xE49B69C1, 0xEFBE4786, 0xFC19DC6, 0x240CA1CC, 0x2DE92C6F, 0x4A7484AA, 0x5CB0A9DC, 0x76F988DA, 0x983E5152, 0xA831C66D, 0xB00327C8, 0xBF597FC7, 0xC6E00BF3, 0xD5A79147, 0x6CA6351, 0x14292967, 0x27B70A85, 0x2E1B2138, 0x4D2C6DFC, 0x53380D13, 0x650A7354, 0x766A0ABB, 0x81C2C92E, 0x92722C85, 0xA2BFE8A1, 0xA81A664B, 0xC24B8B70, 0xC76C51A3, 0xD192E819, 0xD6990624, 0xF40E3585, 0x106AA070, 0x19A4C116, 0x1E376C08, 0x2748774C, 0x34B0BCB5, 0x391C0CB3, 0x4ED8AA4A, 0x5B9CCA4F, 0x682E6FF3, 0x748F82EE, 0x78A5636F, 0x84C87814, 0x8CC70208, 0x90BEFFFA, 0xA4506CEB, 0xBEF9A3F7, 0xC67178F2); 
        let HASH = new Array(0x6A09E667, 0xBB67AE85, 0x3C6EF372, 0xA54FF53A, 0x510E527F, 0x9B05688C, 0x1F83D9AB, 0x5BE0CD19); 
        let W = new Array(64); 
        let a, b, c, d, e, f, g, h, i, j; 
        let T1, T2; 
    m[l >> 5] |= 0x80 << (24 - l % 32); 
    m[((l + 64 >> 9) << 4) + 15] = l; 
    for ( let i = 0; i<m.length; i+=16 ) { 
    a = HASH[0]; 
    b = HASH[1]; 
    c = HASH[2]; 
    d = HASH[3]; 
    e = HASH[4]; 
    f = HASH[5]; 
    g = HASH[6]; 
    h = HASH[7]; 
    for ( let j = 0; j<64; j++) { 
    if (j < 16) W[j] = m[j + i]; 
    else W[j] = safe_add(safe_add(safe_add(Gamma1256(W[j - 2]), W[j - 7]), Gamma0256(W[j - 15])), W[j - 16]); 
    T1 = safe_add(safe_add(safe_add(safe_add(h, Sigma1256(e)), Ch(e, f, g)), K[j]), W[j]); 
    T2 = safe_add(Sigma0256(a), Maj(a, b, c)); 
    h = g; 
    g = f; 
    f = e; 
    e = safe_add(d, T1); 
    d = c; 
    c = b; 
    b = a; 
    a = safe_add(T1, T2); 
    } 
    HASH[0] = safe_add(a, HASH[0]); 
    HASH[1] = safe_add(b, HASH[1]); 
    HASH[2] = safe_add(c, HASH[2]); 
    HASH[3] = safe_add(d, HASH[3]); 
    HASH[4] = safe_add(e, HASH[4]); 
    HASH[5] = safe_add(f, HASH[5]); 
    HASH[6] = safe_add(g, HASH[6]); 
    HASH[7] = safe_add(h, HASH[7]); 
    } 
    return HASH; 
    } 
    function str2binb (str) { 
        let bin = Array(); 
        let mask = (1 << chrsz) - 1; 
    for(let i = 0; i < str.length * chrsz; i += chrsz) { 
    bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (24 - i%32); 
    } 
    return bin; 
    } 
    function Utf8Encode(string) { 
    string = string.replace(/\r\n/g,"\n"); 
    let utftext = ""; 
    for (let n = 0; n < string.length; n++) { 
        let c = string.charCodeAt(n); 
    if (c < 128) { 
    utftext += String.fromCharCode(c); 
    } 
    else if((c > 127) && (c < 2048)) { 
    utftext += String.fromCharCode((c >> 6) | 192); 
    utftext += String.fromCharCode((c & 63) | 128); 
    } 
    else { 
    utftext += String.fromCharCode((c >> 12) | 224); 
    utftext += String.fromCharCode(((c >> 6) & 63) | 128); 
    utftext += String.fromCharCode((c & 63) | 128); 
    } 
    } 
    return utftext; 
    } 
    function binb2hex (binarray) { 
        let hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef"; 
        let str = ""; 
    for(let i = 0; i < binarray.length * 4; i++) { 
    str += hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8+4)) & 0xF) + 
    hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8 )) & 0xF); 
    } 
    return str; 
    } 
    s = Utf8Encode(s); 
    return binb2hex(core_sha256(str2binb(s), s.length * chrsz)); 
}