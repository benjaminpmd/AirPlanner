SRC_DIR = src
BUILD_DIR = build
GXX = gcc

all: clean create_build_dir main
	@echo "Compilation succesfull!"
	@echo "Use make run to run the script"

main: client
	@echo "Compiling main..."
	@$(GXX) $(SRC_DIR)/main.c -o $(BUILD_DIR)/main $(BUILD_DIR)/client.o $(BUILD_DIR)/logger.o
	@echo "Complilation of main.c succesfull!"

client: logger
	@echo "Compiling client..."
	@$(GXX) -c $(SRC_DIR)/client.c -o $(BUILD_DIR)/client.o
	@echo "Complilation of client.c succesfull!"

logger:
	@echo "Compiling logger..."
	@$(GXX) -c $(SRC_DIR)/logger.c -o $(BUILD_DIR)/logger.o
	@echo "Complilation of logger.c succesfull!"

create_build_dir:
	@echo "Creating the build directory"
	mkdir -p $(BUILD_DIR)

run:
	@echo "Running the client"
	@./$(BUILD_DIR)/main

clean:
	@echo "cleaning up..."
	@rm -rf $(BUILD_DIR)
	@echo "cleaned up"