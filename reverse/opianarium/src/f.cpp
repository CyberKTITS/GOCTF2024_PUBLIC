#pragma GCC target("avx2")
// #pragma GCC optimize("O3,unroll-loops")
#include <bits/stdc++.h>
using namespace std;

#define ll long long
#define uint unsigned int
#define ull unsigned long long
#define FOR(i,val,n) for(int i=val;i<n;i++)
#define FORD(i,n) for(int i=0;i<n;i++)
#define DMAT(dp,n,m) for(int i=0;i<n;i++){for(int j=0;j<m;j++)cout << dp[i][j] << ' ';cout << '\n';}

#define INF 18446744073709ull

#include "consts.h"
void dfs(int v,int color){
    for (auto u: g[v]){
        if (colors[u]==colors[v])result=false;
        dfs(u, color^1);
    }
}


int main(){
    // int n; cin >> n;
    // used.resize(g.size(),false);
    // srand(1588);
    // for(long i = 2; i <= 11; ++i) cout << (rand() % (i - 1) ) << " " << i-1 << "\n";
    // colors.resize(g.size(),0);
    // dfs(0,0);
    colors.resize(g.size(),0);

    string input;
    cin >> input;

    string alphabet = "abcdefghijklmnopqrstuvwxyz_?";
    FORD(i,input.size()){
        char ch = input[i];
        if (!((97<=ch && ch<=122)|| ch==95 || ch==63)){
            cout << "Invalid char. Your alphabet is 'abcdefghijklmnopqrstuvwxyz_?1379'\n";
            return 0;
        }
        vector<int> el = matrix[i][alphabet.find(ch)];
        for (int j = 0 ;j < 5; j++){
            colors[j+i*5] = el[j];
        }
    }
    dfs(0,0);

    if (result){
        cout << "YOUR FLAG CAONGRATS: CTF{"  << input << "}\n";
    } else {
        cout << "CHECK FAILED TRY AGAIN";
    }
    return 0;
}