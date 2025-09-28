using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using TechFixApplication.Models.Entities;
using TechFixApplication.Models;
using TechFixApplication.Utili;

namespace TechFixApplication.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class OrderController : ControllerBase
    {
        private readonly DBConnection _dbConnection;

        public OrderController(DBConnection dbConnection)
        {
            _dbConnection = dbConnection;
        }

        [HttpPost]
        public IActionResult AddOrder([FromBody] AddOrderDto addOrderDto)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState); // Return validation errors
            }

            try
            {
                var order = new Orders
                {
                    QuotationId = addOrderDto.QuotationId,
                    SupplierId = addOrderDto.SupplierId,
                    ItemName = addOrderDto.ItemName,
                    Description = addOrderDto.Description,
                    Price = addOrderDto.Price,
                    Quantity = addOrderDto.Quantity,
                    TotalValue = addOrderDto.TotalValue
                };

                _dbConnection.Orders.Add(order);
                _dbConnection.SaveChanges();

                return Ok(order);
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error adding order: {ex.Message}");
                return StatusCode(500, "An error occurred while processing the order.");
            }
        }

        [HttpGet]
        public IActionResult GetOrders()
        {
            try
            {
                var orders = _dbConnection.Orders
                    .Join(
                        _dbConnection.Suppliers,
                        order => order.SupplierId,
                        supplier => supplier.Id,
                        (order, supplier) => new
                        {
                            order.Id,
                            order.ItemName,
                            order.Description,
                            order.Price,
                            order.Quantity,
                            order.TotalValue,
                            order.OrderDate,
                            SupplierName = supplier.Name
                        })
                    .ToList();

                return Ok(orders);
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error fetching orders: {ex.Message}");
                return StatusCode(500, "An error occurred while fetching the orders.");
            }
        }

        [HttpDelete("{id:guid}")]
        public IActionResult DeleteOrder(Guid id)
        {
            try
            {
                var order = _dbConnection.Orders.Find(id);

                if (order == null)
                {
                    return NotFound(new { message = "Order not found." });
                }

                _dbConnection.Orders.Remove(order);
                _dbConnection.SaveChanges();

                return Ok(new { message = "Order deleted successfully." });
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error deleting order: {ex.Message}");
                return StatusCode(500, "An error occurred while deleting the order.");
            }
        }

    }
}
