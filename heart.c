#include <stdio.h>

// Definitions
#define HT 25 // Height of the shape
#define WD 50 // Width of the shape

// Function prototypes
float power(float base, int index);

// Allocates a 2-dimensional array grid for storing points of a function,
// then prints the grid nicely.
// Grid size and function are hard-coded in. Accepting input is left as
// an exercise to the reader. Suckers.
int main(int argc, char *argv[]) {

    int   grid[WD][HT]; // The grid that will store the shape
    int   i, j;         // Counters
    float x, y;         // For storing x and y values of points

    // Store the heart shape as a grid of 1s and 0s
    for (i = 0; i < HT; i++) {
        
        // Define the y-value for the heart function.
        y = (i - HT/2) / (HT/3.0);

        for (j = 0; j < WD; j++) {
            
            // Define the x-value for the heart function.
            x = (j - WD/2) / (WD/2.5);
            
            // Check if a the point lies in the heart function.
            if (power((power(x, 2) + power(y, 2) - 1), 3) - 
                (power(x, 2)*power(y, 3)) <= 0) {
                // If so store a 1
                grid[j][HT-i-1] = 1;
            } else {
                // Otherwise store a 0
                grid[j][HT-i-1] = 0;
            }
        }
    }

    // Print out the grid with 1s replaced by hashes and
    // 0s replaced by spaces.
    for (i = 0; i < HT; i++) {
        for (j = 0; j < WD; j++) {
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

// Raise a number to a given power.
float power(float base, int index) {
    float answer = 1;
    int i;

    for (i = 0; i < index; i++) {
        answer *= base;
    }

    return answer;
}
