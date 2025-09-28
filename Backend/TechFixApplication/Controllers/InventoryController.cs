using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using TechFixApplication.Models.Entities;
using TechFixApplication.Models;
using TechFixApplication.Utili;

namespace TechFixApplication.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class InventoryController : ControllerBase
    {
        private readonly DBConnection dBConnection;
        public InventoryController(DBConnection dBConnection)
        {
            this.dBConnection = dBConnection;
        }

        [HttpGet]
        public IActionResult GetAllInventory()
        {
            var allInventorys = dBConnection.Inventorys.ToList();

            return Ok(allInventorys);

            //return Ok(dBConnection.Employees.ToList());
        }

        [HttpGet]
        [Route("{id:guid}")]
        public IActionResult GetInventoryById(Guid id)
        {

            var inventorys = dBConnection.Inventorys.Find(id);

            if (inventorys == null)
            {
                return NotFound();
            }

            return Ok(inventorys);
        }

        [HttpPost]
        public IActionResult AddStaff(AddInventoryDto addInventoryDto)
        {
            var inventoryEntity = new Inventory()
            {

                SupplierId = addInventoryDto.SupplierId,
                ItemName = addInventoryDto.ItemName,
                Quantity = addInventoryDto.Quantity,
                Price = addInventoryDto.Price,
            };

            dBConnection.Inventorys.Add(inventoryEntity);
            dBConnection.SaveChanges();

            return Ok(inventoryEntity);
        }

        // Update inventory item
        [HttpPut("{id:guid}")]
        public IActionResult UpdateInventory(Guid id, [FromBody] UpdateInventoryDto updateInventoryDto)
        {
            Console.WriteLine($"Received Update Request: ID={id}");

            var inventory = dBConnection.Inventorys.Find(id);

            if (inventory == null)
            {
                Console.WriteLine("Inventory item not found.");
                return NotFound(new { message = "Inventory item not found." });
            }

            Console.WriteLine("Found Inventory Item. Updating fields...");

            // Update fields
            inventory.ItemName = updateInventoryDto.ItemName;
            inventory.Quantity = updateInventoryDto.Quantity;
            inventory.Price = updateInventoryDto.Price;

            dBConnection.SaveChanges();

            Console.WriteLine("Inventory updated successfully.");
            return Ok(inventory);
        }


        [HttpGet("supplier/{supplierId:guid}")]
        public IActionResult GetInventoryBySupplier(Guid supplierId)
        {
            var inventory = dBConnection.Inventorys
                .Where(i => i.SupplierId == supplierId)
                .ToList();

            if (!inventory.Any())
            {
                return NotFound(new { message = "No inventory found for the selected supplier." });
            }

            return Ok(inventory);
        }



        // Delete inventory item
        [HttpDelete("{id:guid}")]
        public IActionResult DeleteInventory(Guid id)
        {
            var inventory = dBConnection.Inventorys.Find(id);

            if (inventory == null)
            {
                return NotFound(new { message = "Inventory item not found." });
            }

            dBConnection.Inventorys.Remove(inventory);
            dBConnection.SaveChanges();

            return Ok(new { message = "Inventory item deleted successfully." });
        }
    }
}
