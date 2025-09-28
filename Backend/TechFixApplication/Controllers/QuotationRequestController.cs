using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using TechFixApplication.Models;
using TechFixApplication.Models.Entities;
using TechFixApplication.Utili;

namespace TechFixApplication.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class QuotationRequestController : ControllerBase
    {
        private readonly DBConnection _dbConnection;

        public QuotationRequestController(DBConnection dbConnection)
        {
            _dbConnection = dbConnection;
        }

        // POST: api/QuotationRequest
        [HttpPost]
        public IActionResult CreateQuotationRequest([FromBody] RequestQuotationDto requestDto)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            var newRequest = new QuotationRequest
            {
                Id = Guid.NewGuid(),
                ItemName = requestDto.ItemName,
                Description = requestDto.Description,
                DateNeeded = requestDto.DateNeeded,
                Quantity = requestDto.Quantity,
                CreatedAt = DateTime.UtcNow
            };

            _dbConnection.QuotationRequests.Add(newRequest);
            _dbConnection.SaveChanges();

            return Ok(new { message = "Quotation request created successfully.", requestId = newRequest.Id });
        }

        // GET: api/QuotationRequest
        [HttpGet]
        public IActionResult GetAllQuotationRequests()
        {
            var requests = _dbConnection.QuotationRequests.ToList();
            return Ok(requests);
        }

        // GET: api/QuotationRequest/{id}
        [HttpGet("{id:guid}")]
        public IActionResult GetQuotationRequestById(Guid id)
        {
            var request = _dbConnection.QuotationRequests.Find(id);

            if (request == null)
            {
                return NotFound(new { message = "Quotation request not found." });
            }

            return Ok(request);
        }

        // PUT: api/QuotationRequest/{id}
        [HttpPut("{id:guid}")]
        public IActionResult UpdateQuotationRequest(Guid id, [FromBody] RequestQuotationDto requestDto)
        {
            var request = _dbConnection.QuotationRequests.Find(id);

            if (request == null)
            {
                return NotFound(new { message = "Quotation request not found." });
            }

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            request.ItemName = requestDto.ItemName;
            request.Description = requestDto.Description;
            request.DateNeeded = requestDto.DateNeeded;
            request.Quantity = requestDto.Quantity;

            _dbConnection.SaveChanges();

            return Ok(new { message = "Quotation request updated successfully.", requestId = request.Id });
        }

        // DELETE: api/QuotationRequest/{id}
        [HttpDelete("{id:guid}")]
        public IActionResult DeleteQuotationRequest(Guid id)
        {
            var request = _dbConnection.QuotationRequests.Find(id);

            if (request == null)
            {
                return NotFound(new { message = "Quotation request not found." });
            }

            _dbConnection.QuotationRequests.Remove(request);
            _dbConnection.SaveChanges();

            return Ok(new { message = "Quotation request deleted successfully." });
        }
    }
}
