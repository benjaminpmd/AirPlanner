SRC_DIR = src
BUILD_DIR = build
GXX = gcc

all: main
	@echo "Compilation succesfull!"
	@echo "Use make run to run the script"

main: client
	@echo "Compiling main..."
	@$(GXX) $(SRC_DIR)/main.c -o $(BUILD_DIR)/main $(BUILD_DIR)/client.o
	@echo "Complilation of main.c succesfull!"

client:
	mkdir -p build
	@echo "Compiling client..."
	@$(GXX) -c $(SRC_DIR)/client.c -o $(BUILD_DIR)/client.o
	@echo "Complilation of client.c succesfull!"

run:
	@echo "Running the client"
	@./$(BUILD_DIR)/main

clean:
	@echo "cleaning up..."
	@rm -r $(BUILD_DIR)
	@echo "cleaned up"