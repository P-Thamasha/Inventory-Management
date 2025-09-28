using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using TechFixApplication.Models.Entities;
using TechFixApplication.Models;
using TechFixApplication.Utili;

namespace TechFixApplication.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class SupplierController : ControllerBase
    {
        private readonly DBConnection dBConnection;

        public SupplierController(DBConnection dBConnection)
        {
            this.dBConnection = dBConnection;
        }

        [HttpGet]
        public IActionResult GetAllSupplier()
        {
            var suppliers = dBConnection.Suppliers
           .Select(s => new
           {
               s.Id,
               s.Name,
               s.Company,
               s.Phone,
               s.Email,
               s.Location

           })
           .ToList();

            return Ok(suppliers);


            //return Ok(dBConnection.Employees.ToList());
        }

        [HttpPost]
        public IActionResult AddSupplier(AddSupplierDto addsupplierDto)
        {
            var supplierEntity = new Supplier()
            {
                Company = addsupplierDto.Company,
                Name = addsupplierDto.Name,
                Email = addsupplierDto.Email,
                Location = addsupplierDto.Location,
                Phone = addsupplierDto.Phone,
                Password = addsupplierDto.Password,
            };

            dBConnection.Suppliers.Add(supplierEntity);
            dBConnection.SaveChanges();

            return Ok(supplierEntity);
        }

        [HttpGet]
        [Route("{id:guid}")]
        public IActionResult GetSupplierById(Guid id)
        {

            var supplier = dBConnection.Suppliers.Find(id);

            if (supplier == null)
            {
                return NotFound();
            }

            return Ok(supplier);
        }

        [HttpDelete]
        [Route("{id:guid}")]
        public IActionResult DeleteSupplier(Guid id)
        {
            var supplier = dBConnection.Suppliers.Find(id);

            if (supplier == null)
            {
                return NotFound();
            }

            dBConnection.Suppliers.Remove(supplier);
            dBConnection.SaveChanges();

            return Ok(supplier);

        }

        [HttpPost("login")]
        public IActionResult LoginStaff(LoginSupplierDto loginSupplierDto)
        {
            // Find staff by email and password
            var supplier = dBConnection.Suppliers
                        .FirstOrDefault(s => s.Email == loginSupplierDto.Email && s.Password == loginSupplierDto.Password);

            if (supplier == null)
            {
                return Unauthorized(new { message = "Invalid email or password." });
            }

            return Ok(new
            {
                message = "Supplier Login successful!",
                supplierId = supplier.Id,
                name = supplier.Name,
                email = supplier.Email
            });
        }
    }
}
