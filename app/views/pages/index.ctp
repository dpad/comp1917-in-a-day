<div class="section"> 
<?php 
foreach ($table_of_contents as $id => $chapter){
    echo "<a href='/chapters/".$chapter['Chapter']['link']."'>".$chapter['Chapter']['name']."</a><br/>";
}
?>
</div>
<div class='section'> 
<p> 
<h2>For the love of code...</h1> 
<div class='codeblock'><pre id='heartoutnolines' class='code'>                              
       #####       #####      
    ########### ###########   
   #########################  
  ########################### 
  ########################### 
  ########################### 
  ########################### 
   #########################  
   #########################  
     #####################    
      ###################     
       #################      
         #############        
            #######           
              ###             
                              
                              
                              
                              
</pre></div>code:
<div class='codeblock'><strong>Download: <a href='arrays/heart.c'>arrays/heart.c</a></strong></br><a href="javascript:toggleLineNos('heart');">Toggle Line Numbers</a><pre id='heartlines' class='code'>01: #include &ltstdio.h&gt
02: 
03: #define HT 20
04: #define WD 30
05: 
06: float power(float base, int index);
07: 
08: int main(int argc, char *argv[]) {
09: 
10:     int grid[WD][HT]; //the grid that will store the shape
11: 
12:     int i, j; //counters
13: 
14:     float x, y; //for storing x and y values of points
15: 
16: 
17:     //store the heart shape as a grid of 1s and 0s
18:     for (i = 0;i&ltHT;i++) {
19:         
20:         y = (i - HT/2)/(HT/3.0);
21: 
22:         for (j = 0;j&ltWD;j++) {
23:             
24:             x = (j - WD/2)/(WD/2.5);
25:             
26:             //check if a the point lies in the heart shape
27:             if (power((power(x, 2) + power(y, 2) - 1), 3) - 
28:                 (power(x, 2)*power(y, 3)) &lt= 0) {
29: 
30:                 //if so store a 1
31:                 grid[j][HT-i-1] = 1;
32:             } else {
33:                 //otherwise store a 0
34:                 grid[j][HT-i-1] = 0;
35:             }
36:         }
37:     }
38: 
39:     //print a heart of hashes
40:     for (i = 0;i&ltHT;i++) {
41:         for (j = 0;j&ltWD;j++) {
42:             if (grid[j][i] == 1) {
43:                 printf("#");
44:             } else {
45:                 printf(" ");
46:             }
47:         }
48:         printf("\n");
49:     }
50: 
51: 
52:     return 0;
53: }
54: 
55: float power(float base, int index) {
56:     float answer = 1;
57:     int i;
58: 
59:     for (i = 0;i&ltindex;i++) {
60:         answer *= base;
61:     }
62: 
63:     return answer;
64: }
</pre><pre id='heartnolines' class='code'style='display:none;'>#include &ltstdio.h&gt
 
#define HT 20
#define WD 30
 
float power(float base, int index);
 
int main(int argc, char *argv[]) {
 
    int grid[WD][HT]; //the grid that will store the shape
 
    int i, j; //counters
 
    float x, y; //for storing x and y values of points
 
 
    //store the heart shape as a grid of 1s and 0s
    for (i = 0;i&ltHT;i++) {
        
        y = (i - HT/2)/(HT/3.0);
 
        for (j = 0;j&ltWD;j++) {
            
            x = (j - WD/2)/(WD/2.5);
            
            //check if a the point lies in the heart shape
            if (power((power(x, 2) + power(y, 2) - 1), 3) - 
                (power(x, 2)*power(y, 3)) &lt= 0) {
 
                //if so store a 1
                grid[j][HT-i-1] = 1;
            } else {
                //otherwise store a 0
                grid[j][HT-i-1] = 0;
            }
        }
    }
 
    //print a heart of hashes
    for (i = 0;i&ltHT;i++) {
        for (j = 0;j&ltWD;j++) {
            if (grid[j][i] == 1) {
                printf("#");
            } else {
                printf(" ");
            }
        }
        printf("\n");
    }
 
 
    return 0;
}
 
float power(float base, int index) {
    float answer = 1;
    int i;
 
    for (i = 0;i&ltindex;i++) {
        answer *= base;
    }
 
    return answer;
}
</pre></div></p> 
</div> 
