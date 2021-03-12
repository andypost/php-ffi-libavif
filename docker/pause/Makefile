all: pause

pause: pause.o
	gcc -Os pause.o -o pause

pause.o: pause.c
	gcc -c pause.c

strip: pause
	strip pause

clean:
	rm -fr pause.o pause