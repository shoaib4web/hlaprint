class PriceOptions {
    constructor(pageSize, colorType, sidedness, noOfPages, shopId, basePrice) {
        this.variation_id = pageSize+"_"+colorType+"_"+sidedness;
        this.pageSize = pageSize;
        this.colorType = colorType;
        this.sidedness = sidedness;
        this.noOfPages = noOfPages;
        this.shopId = shopId;
        this.basePrice = basePrice;
    }

    // Method to display information about the PriceOptions
    displayInfo() {
        console.log(`Page Size: ${this.pageSize}, Color Type: ${this.colorType}, Sidedness: ${this.sidedness}, Number of Pages: ${this.noOfPages}, Shop ID: ${this.shopId}, Base Price: ${this.basePrice}`);
    }

    // Additional methods to manipulate PriceOptions data can be added here
}