#include<stdio.h>
#include<stdlib.h>
int main()
{
	char name[105];
	for(int i=1;i<=5;i++)
	{
		sprintf(name,"slx%d.txt",i);
		freopen(name,"w",stdout);
	}
	return 0;
}