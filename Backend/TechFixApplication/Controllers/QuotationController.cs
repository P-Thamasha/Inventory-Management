using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using TechFixApplication.Models;
using TechFixApplication.Models.Entities;
using TechFixApplication.Utili;

namespace TechFixApplication.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class QuotationController : ControllerBase
    {
        private readonly DBConnection dBConnection;
        public QuotationController(DBConnection dBConnection)
        {
            this.dBConnection = dBConnection;
        }

        [HttpGet]
        public IActionResult GetAllQuotation()
        {
            var allQuotations = dBConnection.Quotations.ToList();

            return Ok(allQuotations);

            //return Ok(dBConnection.Employees.ToList());
        }

        // Add Quotation
        [HttpPost]
        public IActionResult AddQuotation([FromBody] AddQuotationDto addQuotationDto)
        {
            var quotation = new Quotation
            {
                Id = Guid.NewGuid(),
                SupplierId = addQuotationDto.SupplierId,
                CompanyName = addQuotationDto.CompanyName,
                CompanyAddress = addQuotationDto.CompanyAddress,
                PhoneNumber = addQuotationDto.PhoneNumber,
                ItemName = addQuotationDto.ItemName,
                Description = addQuotationDto.Description,
                Price = addQuotationDto.Price
            };

            dBConnection.Quotations.Add(quotation);
            dBConnection.SaveChanges();

            return Ok(quotation);
        }

        // Update Quotation
        [HttpPut("{id:guid}")]
        public IActionResult UpdateQuotation(Guid id, [FromBody] UpdateQuotationDto updateQuotationDto)
        {
            var quotation = dBConnection.Quotations.Find(id);

            if (quotation == null)
            {
                return NotFound(new { message = "Quotation not found." });
            }

            quotation.SupplierId = updateQuotationDto.SupplierId;
            quotation.CompanyName = updateQuotationDto.CompanyName;
            quotation.CompanyAddress = updateQuotationDto.CompanyAddress;
            quotation.PhoneNumber = updateQuotationDto.PhoneNumber;
            quotation.ItemName = updateQuotationDto.ItemName;
            quotation.Description = updateQuotationDto.Description;
            quotation.Price = updateQuotationDto.Price;

            dBConnection.SaveChanges();

            return Ok(quotation);
        }

        // Delete Quotation
        [HttpDelete("{id:guid}")]
        public IActionResult DeleteQuotation(Guid id)
        {
            var quotation = dBConnection.Quotations.Find(id);

            if (quotation == null)
            {
                return NotFound(new { message = "Quotation not found." });
            }

            dBConnection.Quotations.Remove(quotation);
            dBConnection.SaveChanges();

            return Ok(new { message = "Quotation deleted successfully." });
        }

        // Get Quotation by ID
        [HttpGet("{id:guid}")]
        public IActionResult GetQuotationById(Guid id)
        {
            var quotation = dBConnection.Quotations.Find(id);

            if (quotation == null)
            {
                return NotFound(new { message = "Quotation not found." });
            }

            return Ok(quotation);
        }

        [HttpGet("GetQuotationsByItem")]
        public IActionResult GetQuotationsByItem(string itemName)
        {
            var quotations = (from q in dBConnection.Quotations
                              join s in dBConnection.Suppliers on q.SupplierId equals s.Id
                              where q.ItemName == itemName
                              select new
                              {
                                  q.Id,
                                  q.ItemName,
                                  q.Description,
                                  q.Price,
                                  q.CompanyName,
                                  q.SupplierId,
                                  SupplierName = s.Name,
                                  SupplierEmail = s.Email,
                                  SupplierPhone = s.Phone
                              }).ToList();

            if (!quotations.Any())
            {
                return NotFound(new { message = "No quotations found for the selected item." });
            }

            return Ok(quotations);
        }


    }
}
